# SMS Notifications — Where & When SMS Is Sent

_Reference for the SEARCH system. Generated 2026-06-02._

This document lists **every place the system sends an SMS**, what triggers it,
who receives it, and the message sent. Use it as the basis for the SMS usage report.    ` 

---

## 1. How SMS works (architecture)

All SMS flow through one pipeline:

```
SendSmsJob::dispatch(number, message, context, user_id, sender_id)
   → queue (QUEUE_CONNECTION)  → Supervisor / Laravel worker
   → SendSmsJob::handle()      → writes/updates a row in `sms_logs`
   → SmsService::sendSms()     → Semaphore API  → 📱
```

Key facts:

- **Job:** `app/Jobs/SendSmsJob.php` — queued, `tries = 3`, backoff `60/300/900s`.
- **Provider:** Semaphore (sender name `SKSUSEARCH`), via `App\Services\SmsService`.
- **Logging:** every queued send is recorded in the **`sms_logs`** table
  (`status` = `pending` → `sent` / `failed`, plus `error_message`, `context`,
  `user_id`, `sent_at`, `failed_at`).
- **Universal gate:** every dispatch is wrapped in
  `if (!empty($employee_information->contact_number))`.
  **If the recipient has no contact number, no SMS is sent and no log row is created.**
- **Recipient field:** `employee_information.contact_number`.

> ⚠️ **Important distinction:** the test endpoint `POST /api/sms/test-direct`
> sends **directly** via `SmsService` and **does NOT write to `sms_logs`**.
> Only the queued path (`SendSmsJob`) logs. The real application flows below all
> use the queued path, so they are all logged.

---

## 2. Travel Order (TO)

| # | Trigger (when) | Recipient | Message (summary) | Context | Code |
|---|---|---|---|---|---|
| 1 | **TO submitted / created** | **All signatories** (approvers) | "A travel order … submitted … for your approval. Tracking Code: …" | `travel_order_signatory_notification` | `Requisitioner/TravelOrders/TravelOrdersCreate.php:236` |
| 2 | TO type converted → Official Time | Applicants | "Your travel on official business has been converted … to official time…" | `travel_order_type_converted` | `Signatory/TravelOrders/TravelOrdersToSignView.php:68` |
| 3 | TO type converted → Official Business | Applicants | "Your travel on official time has been converted … to official business…" | `travel_order_type_converted` | `Signatory/TravelOrders/TravelOrdersToSignView.php:92` |
| 4 | **Final** approval (all signatories approved) | Applicants | "Your travel order … has been approved by …" | `travel_order_approved` | `Signatory/TravelOrders/TravelOrdersToSignView.php:212` |
| 5 | TO rejected | Applicants | "Your travel order … has been rejected by …" | `travel_order_rejected` | `Signatory/TravelOrders/TravelOrdersToSignView.php:270` |

> **Approver notification = #1 only.** All signatories are texted **once, at
> creation**. There is **no per-step "it's your turn to approve" SMS.**

---

## 3. Disbursement Voucher (DV)

| # | Trigger (when) | Recipient | Message (summary) | Context | Code |
|---|---|---|---|---|---|
| 1 | **DV submitted / created** | **The signatory** (approver) | "A DV has been submitted … for your approval." | `disbursement_voucher_submitted` | `Requisitioner/DisbursementVouchers/DisbursementVouchersCreate.php:930` |
| 2 | Approved & forwarded (signatory) | Requisitioner | "Your DV … has been approved by … and forwarded to …" | `disbursement_voucher_forwarded` | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php:128` |
| 3 | Approved & forwarded (office) | Requisitioner | "Your DV … has been approved by … and forwarded to …" | `disbursement_voucher_forwarded` | `Offices/Traits/OfficeDashboardActions.php:662` |
| 4 | Returned (signatory) | Requisitioner | "Your DV … has been returned by … remarks … Please retrieve …" | `disbursement_voucher_returned` | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php:199` |
| 5 | Returned (office) | Requisitioner | (same as #4) | `disbursement_voucher_returned` | `Offices/Traits/OfficeDashboardActions.php:329` |
| 6 | Returned (office index) | Requisitioner | (same as #4) | `disbursement_voucher_returned` | `Offices/OfficeDisbursementVouchersIndex.php:199` |
| 7 | Returned (OIC office) | Requisitioner | (same as #4) | `disbursement_voucher_returned` | `Oic/OicOfficeDisbursementVouchers.php:123` |
| 8 | Returned (OIC signatory) | Requisitioner | (same as #4) | `disbursement_voucher_returned` | `Oic/OicSignatoryDisbursementVouchers.php:163` |
| 9 | Cheque/ADA ready for disbursement | Requisitioner | "Your DV … is ready for disbursement with check/ADA number …" | `disbursement_voucher_ready` | `Offices/Traits/OfficeDashboardActions.php:425` |

> **Approver notification = #1 only** (at submission, to `$dv->signatory`).
> All other DV texts go to the **requisitioner**. There is **no "needs your
> approval" SMS for downstream office approvers.**

---

## 4. Liquidation Report (LR)

| # | Trigger (when) | Recipient | Message (summary) | Context | Code |
|---|---|---|---|---|---|
| 1 | LR returned (signatory) | Requisitioner | "Your LR … has been returned by … remarks … Please retrieve …" | `liquidation_report_returned` | `Signatory/LiquidationReports/LiquidationReportsIndex.php:160` |
| 2 | LR returned (office) | Requisitioner | (same) | `liquidation_report_returned` | `Offices/OfficeLiquidationReportsIndex.php:165` |
| 3 | LR approved | Requisitioner | "Your LR … has been approved." | `liquidation_report_approved` | `Offices/OfficeLiquidationReportsIndex.php:287` |

---

## 5. Petty Cash Voucher (PCV)

| # | Trigger (when) | Recipient | Message (summary) | Code |
|---|---|---|---|---|
| 1 | PCV issued | Requisitioner | "Petty cash in the amount of P… has been issued … Please liquidate immediately." | `PettyCashVouchers/PettyCashVouchersCreate.php:133` |
| 2 | PCV liquidated | Requisitioner | "Your petty cash … has been liquidated for P… …" | `PettyCashVouchers/PettyCashVouchersIndex.php:113` |

---

## 6. Motorpool (Vehicle Requests)

| # | Trigger (when) | Recipient | Message (summary) | Code |
|---|---|---|---|---|
| 1 | Vehicle changed | Applicant | "Your vehicle … has been changed from … to … coordinate with GSO." | `Requisitioner/Motorpool/RequestVehicleShow.php:487` |
| 2 | Driver changed | Applicant | "Your driver … has been changed from … to … coordinate with GSO." | `Requisitioner/Motorpool/RequestVehicleShow.php:622` |
| 3 | Vehicle request approved | Applicant | "Your vehicle request … has been approved. Your vehicle is … driver is …" | `Requisitioner/Motorpool/RequestVehicleShow.php:752` |

---

## 7. Cash Advance Reminders (liquidation chase ladder)

All in `Requisitioner/DisbursementVouchers/CashAdvanceReminders.php`.

| # | Trigger (when) | Recipient | Message (summary) | Context | Line |
|---|---|---|---|---|---|
| 1 | **FMR** issued (1st reminder) | Accountable officer / payee | "FMR No. … has been sent to you … liquidation deadline is on …" | `FMR` | `192` |
| 2 | **FMD** issued (demand) | Accountable officer / payee | "FMD No. … has been sent to you … deadline was on … FMR No. … was earlier sent." | `FMD` | `322` |
| 3 | **SCO / Memorandum** (show cause) | Accountable officer / payee | "Memorandum No. … ordering you to show cause … FMR/FMD were already sent." | `SCO` | `454` |
| 4 | **Endorsement to Resident Auditor** — payee copy | Payee | "Your unliquidated cash advance … has been endorsed to the Resident Auditor …" | `ENDORSEMENT_PAYEE` | `566` |
| 5 | **Endorsement to Resident Auditor** — auditor copy | Resident Auditor | "The unliquidated cash advance of {payee} … has been endorsed to you …" | `ENDORSEMENT_AUDITOR` | `605` |
| 6 | COA Formal Demand served | Accountable officer / payee | "The Commission on Audit has electronically served your Formal Demand …" | (COA notice) | `755` |

---

## 8. Test / Diagnostic endpoints (`routes/api.php`, no authentication)

Controller: `app/Http/Controllers/Api/SmsTestController.php`

| Method & path | Purpose | Logs to `sms_logs`? |
|---|---|---|
| `POST /api/sms/send` | Queue a test SMS via `SendSmsJob` (same path as real app) | ✅ Yes |
| `POST /api/sms/test-direct` | Send immediately, bypassing the queue | ❌ **No** |
| `GET /api/sms/log/{id}` | Read a single SMS log | — |
| `GET /api/sms/logs` | List recent SMS logs (exposes phone numbers) | — |
| `GET /api/sms/stats` | Success-rate / counts | — |
| `GET /api/sms/provider` | Provider name + `config('services.sms')` (⚠️ may expose API keys) | — |
| `POST /api/sms/format-phone` | Phone-format helper | — |

> 🔒 **Security note:** these routes have **no auth middleware** in
> `routes/api.php`. Anyone can send SMS (cost/abuse), read recipients' numbers,
> and read the provider config. Recommend locking behind auth or removing.

---

## 9. Disabled / not active

- **WFP SMS** (fund allocation, WFP approval, WFP modification) — present but
  **commented out** in `WFP/AllocateFunds.php`, `WFP/WfpSubmissions.php`,
  `WFP/WfpSubmissionsQ1.php`. Not sending.

---

## 10. Summary — who gets an "approval" SMS

Only **two** triggers notify an **approver/signatory**, and both fire **once at
submission**, gated on the signatory having a `contact_number`:

1. **TO submitted** → all signatories (`travel_order_signatory_notification`)
2. **DV submitted** → `$dv->signatory` (`disbursement_voucher_submitted`)

Every other SMS goes to the **document owner** (requisitioner / applicant /
accountable officer), not to approvers. If an approver does not receive an SMS,
the usual cause is a **missing `contact_number`**, or a delivery issue visible in
`sms_logs` (`failed` / no row), **not** missing code for triggers #1 and #2.
