# Bug: concurrent inbound partner requests create duplicate quotes

Two inbound partner requests for the same record, processed at the same time, each create a quote.

**Status:** planned
**Severity:** High. It keeps happening in prod, and each duplicate needs a manual cleanup in the DB. Deleting a twin from the UI marks the shared record as removed (tombstone) and makes the partner drop the record for **both** quotes.

## Steps to reproduce
1. The partner (CIDK) shares a new opportunity that we have no quote for.
2. Two inbound requests for that record reach us at once (likely two webhooks, e.g. opportunity + document, or a webhook overlapping the 15-minute pull).
3. Both run `PartnerSyncService::applyIncoming()` at the same time.

## Expected
Exactly one quote linked to the partner record, however many requests arrive at once.

## Actual
Two identical quotes, both with the same `partner_record_id`, created in the same second.

- **First occurrence (prod, 2026-11-19 19:54:47 UTC):** CIDK record `RFQ1842193` (record_id `4dd1002a-3593-47b7-8edf-a6b2047e93bd`) → quotes 129953 and 129954. 129954 was cleaned up by hand.
- **It happened again in prod after that cleanup.** It recurs, so treat it as systematic, not a one-in-207 fluke.

## Investigation

**Root cause: check-then-act with no lock.** `applyIncoming()` ([app/Partner/PartnerSyncService.inc.php:261-296](../app/Partner/PartnerSyncService.inc.php#L261-L296)):
1. `PartnerShareRepository::findByRecordId()` → none
2. `findByKey()` / `findUnkeyedByCode()` → none
3. `createFromIncoming()` ([:375-427](../app/Partner/PartnerSyncService.inc.php#L375-L427)) → `RepositorioRfq::insertar_cotizacion()`, then a **separate** UPDATE sets `partner_record_id`.

Two PHP processes running this at once both pass steps 1–2 before either reaches step 3. The DB has no safety net: `idx_rfq_partner_record_id` and `idx_rfq_solicitation_key` are non-unique (`sql/partner_opportunity_sync_migration.sql:48-53`). A unique index alone would not fix it cleanly, because `partner_record_id` is written after the INSERT and the losing request would leave an orphan quote.

Nothing in the inbound path runs in a transaction (autocommit), so once a waiting request gets the lock, it sees the first request's rows right away.

**Callers that can collide** (each in its own PHP process):
- `PartnerApi::webhook()` ([app/Partner/PartnerApi.inc.php:113-133](../app/Partner/PartnerApi.inc.php#L113-L133)): `applyIncoming()` after `fetchRecord()`, **and** `applyRemoval()` directly for `opportunity.removed`
- `PartnerSyncService::pull()` ([:674-704](../app/Partner/PartnerSyncService.inc.php#L674-L704)) → `applyIncoming()`, then `reprocessRejected()` ([:548-557](../app/Partner/PartnerSyncService.inc.php#L548-L557)) → `applyIncoming()`
- `resolveReview()` ([:505-530](../app/Partner/PartnerSyncService.inc.php#L505-L530)) **calls `createFromIncoming()` / `applyToQuote()` directly, not through `applyIncoming()`**, and reads its review row with no lock.

**Environment facts that shape the fix**
- MySQL 8 (`lamp-mysql8`): `GET_LOCK` is reentrant per session, and lock names can be up to 64 chars.
- `Conexion` is a **persistent** PDO (`app/Bootstrap/Conexion.inc.php:12`), so a lock that isn't released stays held by the pooled connection after the request ends. `finally` does **not** run on a PHP fatal (memory limit, `max_execution_time` = 30 on the web SAPI).
- `ingestFiles()` downloads partner files (30s timeout each) inside `createFromIncoming()` / `applyToQuote()`, so lock hold time includes downloads.

## Fix Plan

### 1. Per-record MySQL named lock around every inbound apply (`PartnerSyncService`)

```php
public static $lockTimeout = 30;   // test seam

/** Run $fn while holding a per-record MySQL named lock. Returns null if the lock wasn't acquired. */
private static function withRecordLock($conexion, $lockKey, callable $fn) {
    $name = 'partner_rec_' . sha1((string)$lockKey);              // 52 chars, under the 64 limit
    $stmt = $conexion->prepare('SELECT GET_LOCK(:n, :t)');
    $stmt->execute([':n' => $name, ':t' => (int)self::$lockTimeout]);
    if ((int)$stmt->fetchColumn() !== 1) return null;
    // Safety net for fatals (finally won't run) — persistent PDO would otherwise keep the lock.
    register_shutdown_function(function () use ($conexion, $name) {
        try { $conexion->prepare('SELECT RELEASE_LOCK(:n)')->execute([':n' => $name]); } catch (Throwable $e) {}
    });
    try {
        return $fn();
    } finally {
        $conexion->prepare('SELECT RELEASE_LOCK(:n)')->execute([':n' => $name]);
    }
}
```
(Releasing again at shutdown after `finally` already did is harmless: `RELEASE_LOCK` just returns NULL/0.)

- Lock key: `record_id`; when blank, `'key:' . normalizeKey(solicitation_number)`.
- Rename the current bodies to private `applyIncomingUnlocked()` / `applyRemovalUnlocked()`. The public `applyIncoming()` / `applyRemoval()` become thin wrappers that run them inside `withRecordLock()`. Inside the unlocked bodies, call only unlocked versions (`applyIncoming` → `applyRemoval` for removed records).
- Lock not acquired → `['outcome' => 'busy', 'id_rfq' => null]`.

### 2. `resolveReview()` must take the lock itself
Read the review row to get `partner_record_id` (or the key fallback from the payload's solicitation number), then inside `withRecordLock()`:
- **re-read** the review row (`resolved_at IS NULL`); it may have been auto-closed by a concurrent link/create → `not_found`;
- for `create`, check `findByRecordId()` again; if a quote now exists, don't create a second one (return it / `invalid`);
- then run the existing create/link logic.
Busy → return `busy`, and the admin UI should show "Another sync is applying this record — try again" (check `scripts/admin/partner/*` for how outcomes are surfaced).

### 3. Callers handle `busy`
- `PartnerApi::webhook()`: `busy` from **either** `applyIncoming()` or `applyRemoval()` → `503 {"error":"busy"}` so the partner retries.
- `pull()`: a busy record counts as seen, not applied. **If any record was busy, do not advance `last_pull_at`**. `changed_since` filters on the partner's change time, so otherwise the record would never come back. Re-applying is a no-op under the conflict rule, so overlap costs nothing.
- `reprocessRejected()`: nothing special (the row stays open and is retried next pull).

### 4. Resulting behaviour
The second request waits, gets the lock, finds the quote via `findByRecordId()`, and returns `ignored_stale` when it carries the same `shared_updated_at` (no duplicate, no extra audit rows), or `updated` if it's newer. If the first request is still downloading files after 30s, the second gets `busy` → 503 → the partner retries. That's acceptable.

### Files
- `app/Partner/PartnerSyncService.inc.php`: lock helper, wrappers, `resolveReview()`, `pull()`
- `app/Partner/PartnerApi.inc.php`: busy → 503 on both webhook branches
- admin review endpoint under `scripts/admin/partner/`: surface `busy`
- `tests/php/partner_opportunity_sync_test.php`
- `CLAUDE.md`: Partner Opportunity Sync → Inbound

## Tests (`tests/php/partner_opportunity_sync_test.php`)
Tests run inside a rolled-back transaction, and `GET_LOCK` is not transactional:
- **Busy path:** open a second PDO connection (same DSN), `GET_LOCK('partner_rec_' . sha1($id), 0)` on it, set `PartnerSyncService::$lockTimeout = 0`, call `applyIncoming()` → `busy`, and no quote is created. `RELEASE_LOCK` on the second connection in a `finally` and restore the timeout.
- **Removal busy:** same setup with `applyRemoval()` → `busy`, the link is untouched.
- **Sequential replay:** apply the same new record twice → `created` then `ignored_stale`, exactly one quote with that `partner_record_id`.
- **Webhook:** with the lock held elsewhere, `PartnerApi::handle(... 'webhook' ...)` → 503, for both an update notice (needs a `fetchRecord` stub or a seam) and `opportunity.removed`.
- **resolveReview:** `create` on a review whose record already got a quote does not create a second one; with the lock held elsewhere → `busy`.
- **Lock released:** after `applyIncoming()` returns, and after one that throws, `IS_FREE_LOCK(name)` = 1.

## Prod cleanup (for the new occurrence, and any before the fix ships)
Find twins:
```sql
SELECT partner_record_id, GROUP_CONCAT(id ORDER BY id) ids, COUNT(*) n
FROM rfq WHERE partner_record_id IS NOT NULL GROUP BY partner_record_id HAVING n > 1;
```
**Don't delete via the UI.** On the duplicate only (keep the lowest id unless the team already worked the other one), in one transaction:
`deleted = 1, share_with_partner = 0, partner_record_id = NULL, partner_linked_at = NULL, partner_shared_updated_at = NULL, partner_payload_hash = NULL, partner_removed_at = NULL, partner_needs_assignment = 0, solicitation_number = NULL, solicitation_key = NULL`, plus an audit row ("duplicate of #X created by a partner sync race; unlinked without notifying the partner"). Leaving `partner_removed_at` NULL with no timestamp/hash keeps `scanAll()` and `GET /opportunities` from advertising a removal. Clearing the key keeps a later restore-from-trash from colliding with the twin.

## Open Questions
- **Second occurrence details:** confirm it matches the same pattern (same `partner_record_id`, same second). If the twins were seconds or minutes apart, or have *different* record ids with the same Solicitation #, there is a second cause the lock won't cover.
- **Unique index as a backstop:** after prod is clean, consider `UNIQUE (partner_record_id)` (MySQL allows multiple NULLs). It only helps once `createFromIncoming()` can't leave an orphan, e.g. by deleting the just-inserted quote when the UPDATE fails on a duplicate-key error. `/build` decides whether that's worth doing now or later.
- How the admin review UI should word a `busy` result (it's a judgment call).
