# Search breaks on pasted Unicode punctuation

Searching Quotes (or Partial Invoices) with a term that contains a character outside latin1 — e.g. a non-breaking hyphen pasted from a solicitation PDF — fails with a DataTables "Invalid JSON response" alert.

**Status:** fixed

## Steps to reproduce

1. Go to Search Quotes (`/rfq/perfil/search_quotes`).
2. Paste `IFB TC‑12‑09‑26A` where each `‑` is **U+2011 NON-BREAKING HYPHEN** (bytes `e2 80 91`), not ASCII `-`. Typing the term with a normal hyphen does **not** reproduce it.

Command-line repro (no session needed — see Known Bugs in CLAUDE.md):

```bash
t=$'IFB TC‑12‑09‑26A'
curl -s -X POST http://localhost/rfq/utilities/search_quotes -d draw=1 -d start=0 -d length=10 --data-urlencode "searchTerm=$t"
```

## Expected

The search runs and returns matches or "No matching records". Ideally a pasted `IFB TC‑12‑09‑26A` also finds a quote stored as `IFB TC-12-09-26A` (ASCII hyphens), because users paste from documents that contain such characters.

## Actual

- Basic mode: `DataTables warning: table id=tabla_busqueda - Invalid JSON response.` The Partial Invoices table (`search_invoices`) fails the same way.
- Advanced mode: no alert, but the query fails silently and shows "No matching records" even when quotes match.

Response body (basic mode, local):

```
ERROR:SQLSTATE[HY000]: General error: 1267 Illegal mix of collations (latin1_swedish_ci,IMPLICIT) and (utf8mb4_0900_ai_ci,COERCIBLE) for operation 'like'<br>ERROR:SQLSTATE[HY000]: … <br>{"draw":1,"recordsTotal":false,"recordsFiltered":false,"data":[]}
```

## Severity

Partially working. Searches for terms pasted from documents can fail, and advanced search silently returns wrong (empty) results. Seen on prod (`app.e-logic.us`) and reproduced locally.

## Investigation

**When it started:** probably always. The subitem `LIKE` clauses were added with the search improvements in `3c7af979` (2023-07) and copied into advanced search (`211be699`).

**Root cause:** the connection sends the bound term as utf8mb4 (`collation_connection=utf8mb4_0900_ai_ci`), but some searched columns are `latin1_swedish_ci`:

- `subitems.brand`, `subitems.brand_project`, `subitems.part_number`, `subitems.part_number_project` (also `additional` and `website`, which are not searched)
- `invoices.name`

MySQL can coerce a utf8mb4 literal to latin1 only when every character exists in latin1 (cp1252). U+2011 does not, so the `LIKE` fails with error 1267. That's why an en dash `–` or `é` works and `‑` doesn't. utf8mb3 columns (`rfq`, `item`, `services`, `usuarios`) are fine, because MySQL widens them to utf8mb4.

Affected queries in [app/Quote/RepositorioRfq.inc.php](../app/Quote/RepositorioRfq.inc.php):

| Method | Line | latin1 clause | Error handling |
|---|---|---|---|
| `getSearchedQuotesByChannel` | 1557 (subitems EXISTS ~1652) | `si.brand…` | `print 'ERROR:…<br>'` (1674) |
| `getTotalSearchedQuotesByChannelCount` | 1680 (~1723) | `si.brand…` | `print` (1741) |
| `getAdvancedSearchedQuotes` | 1896 (shared clause ~1819) | `si.brand…` | `error_log` + empty result |
| `getAdvancedSearchedQuotesCount` | 1933 | same | `error_log` + 0 |
| `getSearchedInvoices` | 1955 (1981) | `inv.name` | `print` (1993) |
| `getTotalSearchedInvoicesCount` | 1999 (2008) | `inv.name` | `print` (2014) |

The `print` puts HTML in front of the JSON from [scripts/utilities/search_quotes.php](../scripts/utilities/search_quotes.php) and [scripts/utilities/search_invoices.php](../scripts/utilities/search_invoices.php), and DataTables fails to parse it. The endpoints' own `try/catch (Exception)` never fires because the repository swallows the exception. `si.provider_menor` is not a text column and is unaffected.

Ruled out: the session/auth path, the DataTables request params, en dash / NBSP / accents, and every ASCII prefix of the term (all return valid JSON).

## Fix Plan

1. **Normalize the search term** (server side, one shared helper, e.g. a static `RepositorioRfq::normalizeSearchTerm()`; a new class in `app/Utilities/` would need adding to the `index.php` autoload whitelist):
   - U+2010–U+2015, U+2212, U+FE58, U+FE63, U+FF0D → `-`
   - U+00A0, U+2007, U+202F → space
   - U+2018/U+2019 → `'`, U+201C/U+201D → `"`

   Apply it in both endpoints before the term reaches any query (basic, advanced, invoices), so a pasted `IFB TC‑12‑09‑26A` matches `IFB TC-12-09-26A`.
2. **Make the latin1 comparisons safe** in all six queries: `CONVERT(si.brand USING utf8mb4) LIKE :search_term` (and `brand_project`, `part_number`, `part_number_project`, `inv.name`). Any other non-latin1 character that survives normalization (emoji, CJK) then searches normally instead of erroring. Do **not** `ALTER` the tables to utf8mb4 in this fix: it needs a data audit on prod and is a separate job.
3. **Return errors as JSON.** In the six search methods, replace `print 'ERROR:…'` and the swallowing `error_log` with `error_log` + rethrow, so the endpoints' `catch` returns `{"error": …}`. DataTables shows that as a readable alert, and advanced mode stops presenting a failure as "No matching records". Optionally also guard `echo json_encode(...)` against `false`.
4. **Test:** add `tests/php/search_unicode_term_test.php` (transaction-rolled-back, no DDL). Insert a quote with `email_code = 'IFB TC-12-09-26A'` plus an item and a subitem, and an invoice named with ASCII hyphens. Search with the U+2011 term through basic, advanced (list + count) and invoices, and assert that nothing throws or prints and the records are found. Also assert that an emoji term returns an empty result without error. Optionally add a Playwright case to `tests/specs/10-advanced-search.spec.js` that pastes the U+2011 term and checks there's no alert.

## Open Questions

- Where the normalizer lives (static on `RepositorioRfq` vs a small `app/Utilities` class) — `/build`'s call; keep it single-sourced.
- Whether to also normalize client-side in `js/searchQuotes.js` (e.g. so the input shows the cleaned term). Not required: server-side normalization fixes the bug.
- Other `LIKE` searches against latin1 tables (re-quote, fulfillment, tasks, comments, `providers_list`) may have the same issue. They're out of scope here, but `/build` should note any it finds.
