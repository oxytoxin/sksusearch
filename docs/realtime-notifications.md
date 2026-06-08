# Realtime Notification Map

Traceability for every realtime in-app notification (the bell icon) in the system.

## How it works

- Helper: `NotificationController::sendGeneralNotification($type, $title, $message, $receiver, $route = null, $senderId = null)` — `app/Http/Controllers/NotificationController.php`
- It sends `App\Notifications\SystemReminder` over channels `['database', 'broadcast']`:
  - **database** → row in `notifications` table (shown in the bell dropdown)
  - **broadcast** → pushed live on channel `notifications.{user_id}` via laravel-websockets (Pusher protocol)
- Every call site is **additive** (sits beside the SMS/action code), wrapped in its own `try/catch`, and **null-guards the recipient** — a notification failure can never crash the action.
- Future email: add `'mail'` to `SystemReminder::via()` once and every site below inherits it.

Convention: notification blocks are marked with `// ========== REALTIME NOTIFICATION ==========`.

---

## Phase 1 — paired with every SMS (22 added)

Each of these fires alongside an existing `SendSmsJob::dispatch()`, so users without a phone / failed SMS still get notified.

| Module | Event | File | Line | Recipient |
|---|---|---|---|---|
| Travel Order | Created → signatory | `Requisitioner/TravelOrders/TravelOrdersCreate.php` | 264 | Signatories |
| Travel Order | Type converted (OB→OT) | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 83 | Applicants |
| Travel Order | Type converted (OT→OB) | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 124 | Applicants |
| Travel Order | Approved | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 292 | Applicants |
| Travel Order | Rejected | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 367 | Applicants |
| DV | Submitted → signatory | `Requisitioner/DisbursementVouchers/DisbursementVouchersCreate.php` | 951 | Signatory |
| DV | Forwarded (signatory) | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php` | 142 | Requisitioner |
| DV | Returned (signatory) | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php` | 229 | Requisitioner |
| DV | Returned (office) | `Offices/OfficeDisbursementVouchersIndex.php` | 213 | Requisitioner |
| DV | Returned by ICU | `Offices/Traits/OfficeDashboardActions.php` | 342 | Requisitioner |
| DV | Forwarded (office) | `Offices/Traits/OfficeDashboardActions.php` | 691 | Requisitioner |
| DV | Returned (OIC office) | `Oic/OicOfficeDisbursementVouchers.php` | 137 | Requisitioner |
| DV | Returned (OIC signatory) | `Oic/OicSignatoryDisbursementVouchers.php` | 177 | Requisitioner |
| Liquidation Report | Returned (signatory) | `Signatory/LiquidationReports/LiquidationReportsIndex.php` | 174 | Requisitioner |
| Liquidation Report | Returned (office) | `Offices/OfficeLiquidationReportsIndex.php` | 179 | Requisitioner |
| Liquidation Report | Approved/certified | `Offices/OfficeLiquidationReportsIndex.php` | 317 | Requisitioner |
| Petty Cash | Issued | `PettyCashVouchers/PettyCashVouchersCreate.php` | 149 | Requisitioner |
| Petty Cash | Liquidated | `PettyCashVouchers/PettyCashVouchersIndex.php` | 129 | Requisitioner |
| Motorpool | Vehicle changed | `Requisitioner/Motorpool/RequestVehicleShow.php` | 502 | Applicants |
| Motorpool | Driver changed | `Requisitioner/Motorpool/RequestVehicleShow.php` | 653 | Applicants |
| Motorpool | Vehicle/driver confirmed | `Requisitioner/Motorpool/RequestVehicleShow.php` | 799 | Applicants |
| Cash Advance | Endorsement → payee | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 647 | Payee *(uses `sendCASystemReminder`)* |

---

## Phase 2 — new events that had no notification (8 added)

| Module | Event | File | Line | Recipient |
|---|---|---|---|---|
| DV | Cancellation requested | `Requisitioner/DisbursementVouchers/DisbursementVouchersIndex.php` | 125 | Signatory |
| DV | Cancellation approved (office) | `Offices/OfficeDisbursementVouchersIndex.php` | 259 | Requisitioner |
| DV | Cancellation approved (signatory) | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php` | 279 | Requisitioner |
| DV | Cancellation approved (OIC) | `Oic/OicSignatoryDisbursementVouchers.php` | 227 | Requisitioner |
| OIC | Designated as Officer-in-Charge | `Oic/OicAssign.php` | 130 | The designated OIC |
| Liquidation Report | Submitted → signatory | `Requisitioner/LiquidationReports/LiquidationReportsCreate.php` | 452 | Signatory |
| Travel Order | Applicant removed | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 167 | The applicant |
| Travel Order | Applicant restored | `Signatory/TravelOrders/TravelOrdersToSignView.php` | 197 | The applicant |

---

## Pre-existing notifications (not added in this work — listed for completeness)

| Module | Event | File | Line |
|---|---|---|---|
| Cash Advance | FMR sent | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 134 |
| Cash Advance | FMD sent | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 259 |
| Cash Advance | SCO sent | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 390 |
| Cash Advance | Endorsement → auditor | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 518 |
| Cash Advance | FD uploaded | `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php` | 714 |
| DV | Cheque/ADA ready | `Offices/Traits/OfficeDashboardActions.php` | ~430 (`cashAdvanceCreation`) |
| Cash Advance | Message thread reply | `Requisitioner/MessageReplySection.php` | 129, 214 |
| Cash Advance | Automated step reminders (cron) | `Console/Commands/CheckCashAdvanceReminders.php` | 96, 110, 124, 138 |

---

## Notes for maintainers

- **Excluded on purpose:** per-office "Receive" and intra-office steps (Verify/ORS/Certify) — the DV already notifies on every *forward*, so adding these would create noise.
- **Deferred (pending client confirmation):** WFP approval / modification / fund allocation — SMS code there is commented `"TO BE CONFIRMED BY ACCOUNTANT"`; message/policy not finalized.
- Line numbers reflect the state at merge commit `2f8d9495` on branch `proxy`; re-grep `sendGeneralNotification(` if the files change.
- All SMS sending remains via `SendSmsJob::dispatch()` (paid). Realtime notifications are free and independent — one channel failing never affects the other.
