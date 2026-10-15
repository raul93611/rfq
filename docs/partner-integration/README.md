# Partner integration — documents for the partner

What E-logic sends to the partner company's engineers. **Contains no credentials**: the token and the
signing secret are exchanged out of band (a password-manager share or similar), never with these files.

| File | What it is |
| --- | --- |
| `elogic-partner-api.openapi.yaml` | The API E-logic hosts (`api/partner/*`) and the webhook E-logic sends. Import into Postman or a code generator. |
| `partner-endpoints-expected.openapi.yaml` | The contract the partner implements so E-logic can pull from it and notify it. Treat as a server-side contract. |

The human-readable **Integration Guide** (flow, signing, matching and conflict rules, files, statuses,
open questions, test plan) is a shared document and is the first thing the partner should read. Send
the guide as a PDF export together with these two files, and keep the three at the same version.

## Keeping it true

These files describe `app/Partner/PartnerApi.inc.php` and `PartnerSyncService::buildPayload()` /
`normalizeIncoming()`. When the payload mapping changes (it is still provisional until the partner's sample
payload is agreed), update the specs and bump `info.version`, then re-send them.

Quick consistency checks that were used when these were written:

- Both files are structurally valid OpenAPI 3.1 (parse, every `$ref` resolves, path parameters declared,
  unique operation ids).
- The real API responses (live opportunities, tombstones, single fetches, `401`, `404`) were checked against
  the schemas in `elogic-partner-api.openapi.yaml`.
