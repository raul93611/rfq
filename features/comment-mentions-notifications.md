# Comment Mentions & Notifications

**One-line description:** @mention users in quote/fulfillment comments, with real-time in-app and email notifications delivered via per-user Microsoft OAuth.

**Status:** planned

---

## User Flow

### Connecting a Microsoft Account (one-time setup)
1. User navigates to the new **My Account** page (accessible to all logged-in users).
2. User can edit their personal info (first name, last name, email, password) and save.
3. User sees a "Connect Microsoft Account" button.
4. Clicking it redirects to the Microsoft OAuth consent screen (authorization code flow, delegated `Mail.Send` + `offline_access` + `openid` scopes).
5. After consent, Microsoft redirects to `/rfq/perfil/microsoft/callback` with an auth code.
6. The app exchanges the code for an access token + refresh token and stores both in the `usuarios` table.
7. The button now shows "Connected ✓ (disconnect)" — user can revoke at any time, which deletes the stored tokens.

### Leaving a Comment with an @mention
1. User opens a quote or fulfillment page and focuses the comment textarea.
2. User types `@` — a dropdown appears filtered by typed characters, listing matching usernames.
3. User selects a username — `@username` is inserted into the textarea.
4. User submits the comment (existing form/script).
5. Server parses the saved comment text for `@username` patterns, resolves each to a user ID.
6. For each @mentioned user (excluding the commenter):
   - A notification row is inserted into the `notifications` table.
   - If that user has a connected Microsoft account: an email is sent to their MS email via Graph API delegated send.
   - If not connected: email is skipped; in-app notification only.
7. The designated user of the quote (`rfqs.id_usuario`) also receives a notification for any comment posted (excluding when they are the commenter). Same email logic applies.
8. If a user is both @mentioned and the designated user, they receive only one notification (deduplicated).

### Receiving In-App Notifications
1. A bell icon with an unread count badge appears in the navbar for all logged-in users.
2. On page load the browser opens a persistent SSE connection to `GET /rfq/quote/notifications/stream`. The server holds the connection open in a loop, polling the DB every ~3s and pushing an event the moment the unread count changes. The badge updates instantly client-side with no page reload.
3. Clicking the bell opens a dropdown showing recent unread notifications, each with:
   - A short message (e.g. "John mentioned you in Quote #142" or "Mary commented on your quote #88")
   - A relative timestamp
4. Dropdown footer has two actions: **Mark all as read** and **See all notifications**.
5. Clicking any notification item marks it as read and navigates to the relevant quote or fulfillment page.
6. "See all notifications" opens the dedicated **Notifications page** — a full list of all notifications (read and unread), paginated, with a "Mark all as read" button at the top.

---

## UI Changes

| Screen | Change |
|---|---|
| Navbar | Add bell icon with unread badge; notification dropdown (recent items, mark-all-read, see-all link) |
| New: `perfil/account` | My Account page — personal info form + Microsoft connect/disconnect section |
| Comment textarea (quote + fulfillment) | @mention autocomplete dropdown appears on `@` keypress |
| Comment display (quote + fulfillment) | `@username` tokens rendered as highlighted inline chips |
| New: `perfil/notifications` | Full notification history page — paginated list, mark-all-read button |

---

## Data Model Changes

### `usuarios` table — add columns
```sql
ms_refresh_token TEXT NULL,
ms_access_token  TEXT NULL,
ms_token_expiry  INT NULL,
ms_email         VARCHAR(255) NULL
```

### New `notifications` table
```sql
CREATE TABLE notifications (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  id_user     INT NOT NULL,              -- recipient
  id_rfq      INT NOT NULL,
  message     VARCHAR(255) NOT NULL,
  url         VARCHAR(500) NOT NULL,     -- link to quote or fulfillment page
  is_read     TINYINT(1) NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_read (id_user, is_read),
  INDEX idx_created (created_at)
);
```

### `comments` table — no schema change
The raw comment text stores `@username` as plain text. Parsing happens server-side on insert.

---

## External Dependencies

- **Microsoft Azure app registration**: Must add delegated permissions (`Mail.Send`, `offline_access`, `openid`, `profile`, `email`) and a redirect URI pointing to the production callback URL (e.g. `https://<droplet-domain>/rfq/perfil/microsoft/callback`).
- **New Graph API scopes**: Existing client-credentials token (used for SharePoint) is separate. Per-user tokens are stored per user and refreshed on demand.

---

## New Routes

| Route | Handler | Purpose |
|---|---|---|
| `perfil/account` | `plantillas/user/my_account.inc.php` | My Account page |
| `perfil/microsoft/connect` | `scripts/user/microsoft_connect.php` | Initiate OAuth redirect |
| `perfil/microsoft/callback` | `scripts/user/microsoft_callback.php` | Handle OAuth code exchange |
| `perfil/microsoft/disconnect` | `scripts/user/microsoft_disconnect.php` | Delete stored tokens |
| `perfil/notifications` | `plantillas/user/notifications.inc.php` | Notifications page |
| `quote/notifications/stream` | `scripts/quote/notifications_stream.php` | SSE endpoint — holds connection open, pushes `{count: N, items: [...]}` when unread count changes |
| `quote/notifications/list` | `scripts/quote/notifications_list.php` | Returns recent unread notifications as JSON for dropdown |
| `quote/notifications/mark_read` | `scripts/quote/notifications_mark_read.php` | Mark one or all as read |

---

## Acceptance Criteria

- [ ] Typing `@` in any comment textarea (quote or fulfillment) opens a username autocomplete dropdown; selecting inserts `@username` into the text.
- [ ] Submitting a comment with `@username` creates an in-app notification for that user (skipped if commenter = recipient).
- [ ] Submitting any comment creates an in-app notification for the quote's designated user (skipped if commenter = designated user).
- [ ] If a user is both @mentioned and the designated user, they receive exactly one notification.
- [ ] Bell icon shows correct unread count; count updates in real-time via SSE (typically within ~3s of a new notification being written).
- [ ] Clicking the bell opens a dropdown with recent notifications, relative timestamps, "Mark all as read", and "See all".
- [ ] Clicking a notification item marks it read and navigates to the correct quote or fulfillment page.
- [ ] Notifications page shows full history, paginated, with a mark-all-read button.
- [ ] My Account page lets any logged-in user edit their first name, last name, email, and password.
- [ ] My Account page shows "Connect Microsoft Account" button when not connected; shows "Connected ✓ (disconnect)" when connected.
- [ ] After connecting, email notifications are sent via Graph API delegated send for all subsequent comment events targeting that user.
- [ ] If MS account not connected, email is silently skipped — in-app notification still fires.
- [ ] `@username` in rendered comments is displayed as a highlighted inline chip.
- [ ] Feature works identically on Quote and Fulfillment comment sections.

---

## Out of Scope

- Comments in the re-quote page (no comment section exists there).
- Notification preferences or per-user toggles (all notifications are ON).
- Email digest / batched notifications.
- Microsoft Teams notifications.
- WebSockets or third-party push services (SSE is sufficient).
- Admin-level notification management UI.
- Read receipts or "seen by" indicators on comments.
