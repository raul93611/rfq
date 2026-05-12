# RFQ Playwright Test Suite

End-to-end tests for the RFQ application at `http://localhost/rfq`.

## Prerequisites

- Docker LAMP stack running (`localhost/rfq` accessible)
- MySQL accessible at `127.0.0.1:3306` (mapped from `lamp-mysql8` container)
- Node.js ≥ 18

## Setup

```bash
cd tests
npm install
npx playwright install chromium
```

## Running

```bash
# Run all tests (headless)
npm test

# Run with browser visible
npm run test:headed

# Interactive UI mode
npm run test:ui

# View last HTML report
npm run report
```

## How it works

1. **`global-setup.js`** — Creates a test user (`pw_test_user`) and a full test quote with item, subitem, provider, provider-subitem, and service via direct DB connection. Writes IDs to `.fixtures.json`.
2. **`specs/auth.setup.js`** — Logs in as the test user and saves the session cookie to `.auth/session.json`. All other tests reuse this session.
3. **Specs** — Each spec reads `.fixtures.json` for IDs. Tests that create data clean up after themselves via direct DB queries.
4. **`global-teardown.js`** — Deletes all test data (audit trails → items/services → rfq → user) and removes `.fixtures.json`.

## Coverage

| Spec | Area |
|---|---|
| `01-auth.spec.js` | Login, logout, redirect |
| `02-quote-list.spec.js` | Dashboard, navigation, page structure |
| `03-quote-editing-items.spec.js` | Add/edit/delete items via modals |
| `04-quote-editing-providers.spec.js` | Add/edit/delete item providers |
| `05-quote-editing-subitems.spec.js` | Add/edit/delete subitems and their providers |
| `06-services.spec.js` | Add/edit/delete/duplicate services |
| `07-delete-confirmation.spec.js` | `#alert_delete_system` modal (no browser confirm) |
| `08-calculations.spec.js` | Bottom bar totals, services payment term |
