# SendSmsJob Usage Summary - Complete System Overview

## Summary Statistics

| Status                                              | Count            | Files        |
| --------------------------------------------------- | ---------------- | ------------ |
| ✅ **LIVE IN PRODUCTION** (Real Recipients)         | 18 instances     | 10 files     |
| 📋 **REVIEWED - NOT ACTIVE** (Awaiting Approval)    | 6 instances      | 3 files      |
| 🛠 Diagnostic API (manual verification endpoint)    | 1 instance       | 1 file       |
| **Total**                                           | **25 instances** | **14 files** |

**18 SMS notifications LIVE in production — each dispatch sends to the recipient's `contact_number` from `employee_information`. 6 SMS reviewed but NOT activated (awaiting accountant approval). All 24 SMS reviewed and ready.**

**Live verification (real-world example):** A real send to `09XXXXXXXXX` succeeded via Semaphore on 2026-04-29 (message_id `XXXXXXXXX`, sender `SKSUSEARCH`, recipient formatted to `+639XXXXXXXXX`).

---

## ✅ LIVE SMS Notifications (Production)

### 1. Travel Orders (3 instances) ✅ **LIVE**

**File:** `app/Http/Livewire/Signatory/TravelOrders/TravelOrdersToSignView.php`

| Line    | SMS Type               | Context                       | Recipient      | Message                                                                | Status      |
| ------- | ---------------------- | ----------------------------- | -------------- | ---------------------------------------------------------------------- | ----------- |
| 68-75   | Travel Order Converted | `travel_order_type_converted` | All Applicants | "Your travel on official business has been converted..."               | ✅ **LIVE** |
| 212-219 | Travel Order Approved  | `travel_order_approved`       | All Applicants | "Your travel order with ref. no. {tracking_code} has been approved..." | ✅ **LIVE** |
| 270-277 | Travel Order Rejected  | `travel_order_rejected`       | All Applicants | "Your travel order with ref. no. {tracking_code} has been rejected..." | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ All 3 LIVE (Production — uses each applicant's `employee_information->contact_number`)
**Verification:**

-   ✅ All models verified (TravelOrder, User, EmployeeInformation)
-   ✅ All relationships verified (applicants, employee_information)
-   ✅ All columns verified (tracking_code, contact_number)
-   ✅ Null safety implemented
-   ✅ SendSmsJob parameters correct

---

### 2. Travel Order Signatory Notification (1 instance) ✅ **LIVE**

**File:** `app/Http/Livewire/Requisitioner/TravelOrders/TravelOrdersCreate.php`

| Line    | SMS Type               | Context                               | Recipient       | Message                                                                | Status      |
| ------- | ---------------------- | ------------------------------------- | --------------- | ---------------------------------------------------------------------- | ----------- |
| 236-243 | Signatory Notification | `travel_order_signatory_notification` | All Signatories | "A travel order and its accompanying itinerary have been submitted..." | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ LIVE (Production — uses each signatory's `employee_information->contact_number`)
**Verification:**

-   ✅ User model verified (whereIn query)
-   ✅ Eager loading verified (employee_information)
-   ✅ All columns verified (tracking_code, contact_number)
-   ✅ Null safety implemented
-   ✅ SendSmsJob parameters correct
-   ✅ Syntax error fixed (removed stray closing brace)

---

### 3. Vehicle/Driver Notifications (3 instances) ✅ **LIVE**

**File:** `app/Http/Livewire/Requisitioner/Motorpool/RequestVehicleShow.php`

| Line    | SMS Type                 | Context                    | Recipient      | Message                                                    | Status      |
| ------- | ------------------------ | -------------------------- | -------------- | ---------------------------------------------------------- | ----------- |
| 487-494 | Vehicle Changed          | `vehicle_changed`          | All Applicants | "The vehicle assigned to your request has been changed..." | ✅ **LIVE** |
| 622-629 | Driver Changed           | `driver_changed`           | All Applicants | "The driver assigned to your request has been changed..."  | ✅ **LIVE** |
| 752-759 | Vehicle/Driver Confirmed | `vehicle_driver_confirmed` | All Applicants | "Your vehicle request has been confirmed..."               | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ All 3 LIVE (Production — uses each applicant's `employee_information->contact_number`)
**Verification:**

-   ✅ RequestSchedule model verified
-   ✅ applicants() relationship verified (belongsToMany User via request_applicants)
-   ✅ Eager loading verified (employee_information)
-   ✅ All columns verified (contact_number)
-   ✅ Database table verified (request_applicants migration exists)
-   ✅ Null safety implemented (checks employee_information and contact_number)
-   ✅ SendSmsJob parameters correct
-   ✅ Message variables properly constructed for all 3 notifications

---

### 4. Petty Cash Vouchers (2 instances) ✅ **LIVE**

**File 1:** `app/Http/Livewire/PettyCashVouchers/PettyCashVouchersIndex.php`

| Line    | SMS Type       | Context                         | Recipient     | Message                                                                      | Status      |
| ------- | -------------- | ------------------------------- | ------------- | ---------------------------------------------------------------------------- | ----------- |
| 113-120 | PCV Liquidated | `petty_cash_voucher_liquidated` | Requisitioner | "Your petty cash with PCV ref. no. {tracking_number} has been liquidated..." | ✅ **LIVE** |

**File 2:** `app/Http/Livewire/PettyCashVouchers/PettyCashVouchersCreate.php`

| Line    | SMS Type   | Context                     | Recipient     | Message                        | Status      |
| ------- | ---------- | --------------------------- | ------------- | ------------------------------ | ----------- |
| 133-140 | PCV Issued | `petty_cash_voucher_issued` | Requisitioner | "Petty cash voucher issued..." | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ Both LIVE (Production — uses requisitioner's `employee_information->contact_number`)
**Verification:**

-   ✅ PettyCashVoucher model verified
-   ✅ requisitioner() relationship verified (belongsTo User)
-   ✅ Eager loading verified (requisitioner.employee_information)
-   ✅ All columns verified (tracking_number, contact_number)
-   ✅ Database table verified (petty_cash_vouchers migration exists)
-   ✅ Null safety implemented (checks requisitioner, employee_information, contact_number)
-   ✅ SendSmsJob parameters correct
-   ✅ Message variables properly constructed (tracking_number, amounts, refund/reimbursement text)

---

### 5. Cash Advance Reminders (5 instances) ✅ **LIVE** 🌟

**File:** `app/Http/Livewire/Requisitioner/DisbursementVouchers/CashAdvanceReminders.php`

| Block Lines | Dispatch Line | SMS Type            | Context                                    | Recipient       | Message                                                                         | Status      |
| ----------- | ------------- | ------------------- | ------------------------------------------ | --------------- | ------------------------------------------------------------------------------- | ----------- |
| 146-220     | 192-198       | FMR                 | `FMR`                                      | Payee           | "FMR No. {number} has been sent to you for your unliquidated cash advance..."   | ✅ **LIVE** |
| 274-355     | 322-328       | FMD                 | `FMD`                                      | Payee           | "FMD No. {number} has been sent to you... FMR No. {number} was earlier sent..." | ✅ **LIVE** |
| 408-491     | 454-460       | SCO                 | `SCO`                                      | Payee           | "Memorandum No. {number} has been sent to you, ordering you to show cause..."   | ✅ **LIVE** |
| 530-634     | 566-572 / 605-611 | Endorsement (2 SMS) | `ENDORSEMENT_PAYEE`, `ENDORSEMENT_AUDITOR` | Payee + Auditor | Two separate messages: one to payee, one to auditor                             | ✅ **LIVE** |
| 720-805     | 755-761       | FD (Formal Demand)  | `FD`                                       | Payee           | "The Commission on Audit has electronically served your Formal Demand..."       | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ All 5 LIVE (Production — uses payee/auditor `employee_information->contact_number`)
**Implementation Quality:** 🌟 **EXCELLENT** - Enterprise-grade implementation
**Verification:**

-   ✅ 100% Null Safety - All data access protected with null coalescing operators
-   ✅ 100% Error Handling - Comprehensive try-catch blocks that don't block main actions
-   ✅ 100% Logging - Success, warnings, and errors all logged with context
-   ✅ Models verified (CaReminderStep, DisbursementVoucher, User, EmployeeInformation)
-   ✅ All relationships verified (disbursementVoucher, user, employee_information, auditor)
-   ✅ All columns verified (contact_number, cheque_number, total_sum, etc.)
-   ✅ SendSmsJob parameters correct for all 5 notifications
-   ✅ Message variables properly constructed with comprehensive null safety
-   ✅ Multiple recipients handled (Endorsement: 2 SMS to different recipients)
-   ✅ Independent SMS dispatches - failures don't affect each other
-   ✅ Live in production — dispatches use payee/auditor `contact_number` from `employee_information`

**Special Features:**

-   🎯 Sequential escalation process (FMR → FMD → SCO → Endorsement → FD)
-   🎯 Historical context included (references previous notices)
-   🎯 Dual notification for Endorsement (payee + auditor)
-   🎯 Comprehensive logging for audit trail
-   🎯 Graceful degradation - SMS failures don't block main workflow

---

---

## 📋 REVIEWED SMS Notifications (NOT ACTIVE - Awaiting Approval)

---

### 6. Work & Financial Plan (6 instances) 📋 **REVIEWED - NOT ACTIVE** 🌟

**Status:** Awaiting accountant confirmation before activation

#### File 1: `app/Http/Livewire/WFP/AllocateFunds.php` (2 instances)

| Line    | SMS Type            | Context               | Recipient        | Message                                                                                | Review Status   |
| ------- | ------------------- | --------------------- | ---------------- | -------------------------------------------------------------------------------------- | --------------- |
| 93-194  | Fund Allocation     | `FUND_ALLOCATION`     | Cost Center Head | "You have been allocated a fund of ₱{amount} under Fund {fund} {mfo} {cost_center}..." | 📋 **REVIEWED** |
| 239-340 | Fund 161 Allocation | `FUND_ALLOCATION_161` | Cost Center Head | "You have been allocated a fund of ₱{amount} under Fund {fund} {mfo} {cost_center}..." | 📋 **REVIEWED** |

#### File 2: `app/Http/Livewire/WFP/WfpSubmissions.php` (2 instances)

| Line    | SMS Type         | Context            | Recipient        | Message                                                                              | Review Status   |
| ------- | ---------------- | ------------------ | ---------------- | ------------------------------------------------------------------------------------ | --------------- |
| 130-248 | WFP Approved     | `WFP_APPROVAL`     | Cost Center Head | "Your expenditure programming... has been approved. You programmed ₱{amount}..."     | 📋 **REVIEWED** |
| 273-388 | WFP Modification | `WFP_MODIFICATION` | Cost Center Head | "Your expenditure programming... has been returned for modification with remarks..." | 📋 **REVIEWED** |

#### File 3: `app/Http/Livewire/WFP/WfpSubmissionsQ1.php` (2 instances)

| Line    | SMS Type              | Context               | Recipient        | Message                                                                              | Review Status   |
| ------- | --------------------- | --------------------- | ---------------- | ------------------------------------------------------------------------------------ | --------------- |
| 141-259 | WFP Approved (Q1)     | `WFP_APPROVAL_Q1`     | Cost Center Head | "Your expenditure programming... has been approved. You programmed ₱{amount}..."     | 📋 **REVIEWED** |
| 284-399 | WFP Modification (Q1) | `WFP_MODIFICATION_Q1` | Cost Center Head | "Your expenditure programming... has been returned for modification with remarks..." | 📋 **REVIEWED** |

**Review Date:** 2025-11-30
**Status:** 📋 All 6 REVIEWED - NOT ACTIVATED (Awaiting Accountant Approval)
**Implementation Quality:** 🌟 **EXCELLENT** - Enterprise-grade implementation

**Comprehensive Verification:**

-   ✅ 100% Null Safety - All relationship chains protected
-   ✅ 100% Error Handling - Comprehensive try-catch blocks
-   ✅ 100% Logging - Detailed warnings and info logs with context
-   ✅ Models verified (CostCenter, Office, EmployeeInformation, User, Wfp, WpfType, FundCluster, MFO)
-   ✅ All relationships verified:
    -   CostCenter->office() ✅
    -   Office->head_employee() ✅
    -   EmployeeInformation->user() ✅
    -   CostCenter->fundClusterWFP() ✅
    -   CostCenter->mfo() ✅
    -   Wfp->costCenter() ✅
    -   Wfp->fundClusterWfp() ✅
    -   Wfp->wfpType() ✅
-   ✅ All columns verified (contact_number, program_allocated, total_allocated_fund)
-   ✅ SendSmsJob parameters correct for all 6 notifications
-   ✅ Message variables properly constructed with comprehensive null safety
-   ✅ Non-blocking error handling - SMS failures won't block WFP actions
-   ✅ When uncommented, will dispatch to the cost-center-head's real `contact_number` (the example fallback number `09273464891` only appears inside the commented-out fallback line, not as the active recipient)

**Complex Relationship Chain:**

```
CostCenter → Office → head_employee (EmployeeInformation) → User → contact_number
```

**Note:** All implementations are production-ready. Simply uncomment the code blocks when accountant approves.

---

---

### 7. Disbursement Vouchers (2 instances) ✅ **LIVE**

**File 1:** `app/Http/Livewire/Requisitioner/DisbursementVouchers/DisbursementVouchersCreate.php`

| Line    | SMS Type     | Context                          | Recipient | Message                                                                      | Status      |
| ------- | ------------ | -------------------------------- | --------- | ---------------------------------------------------------------------------- | ----------- |
| 930-937 | DV Submitted | `disbursement_voucher_submitted` | Signatory | "A DV has been submitted to the SEARCH system by {maker} for your approval." | ✅ **LIVE** |

**File 2:** `app/Http/Livewire/Offices/Traits/OfficeDashboardActions.php`

| Line    | SMS Type | Context                      | Recipient            | Message                                                                                                    | Status      |
| ------- | -------- | ---------------------------- | -------------------- | ---------------------------------------------------------------------------------------------------------- | ----------- |
| 237-244 | DV Ready | `disbursement_voucher_ready` | Requisitioner (User) | "Your DV with ref. no. {tracking_number} is ready for disbursement with check/ADA number {cheque_number}." | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ Both LIVE (Production — uses signatory/requisitioner `employee_information->contact_number`)
**Verification:**

-   ✅ Models verified (DisbursementVoucher, User, EmployeeInformation)
-   ✅ All relationships verified:
    -   DisbursementVoucher->signatory() ✅
    -   DisbursementVoucher->user() ✅
    -   User->employee_information ✅
-   ✅ All columns verified (tracking_number, contact_number)
-   ✅ SendSmsJob parameters correct
-   ✅ Null safety implemented
-   🔧 **BUG FIXED:** Changed `requested_by` to `user` in OfficeDashboardActions.php (critical fix to prevent crash)

---

### 8. Liquidation Reports (2 instances) ✅ **LIVE**

**File:** `app/Http/Livewire/Offices/OfficeLiquidationReportsIndex.php`

| Line    | SMS Type             | Context                       | Recipient            | Message                                                                                       | Status      |
| ------- | -------------------- | ----------------------------- | -------------------- | --------------------------------------------------------------------------------------------- | ----------- |
| 165-172 | Liquidation Returned | `liquidation_report_returned` | Requisitioner (User) | "Your LR with ref. no. {tracking_number} has been returned by {officer_name} with remarks..." | ✅ **LIVE** |
| 287-294 | Liquidation Approved | `liquidation_report_approved` | Requisitioner (User) | "Your LR with ref. no. {tracking_number} has been approved."                                  | ✅ **LIVE** |

**Review Date:** 2025-11-30
**Status:** ✅ Both LIVE (Production — uses requisitioner `employee_information->contact_number`)
**Verification:**

-   ✅ Models verified (LiquidationReport, DisbursementVoucher, User, EmployeeInformation)
-   ✅ All relationships verified:
    -   LiquidationReport->disbursement_voucher() ✅
    -   DisbursementVoucher->user() ✅
    -   User->employee_information ✅
-   ✅ All columns verified (tracking_number, contact_number)
-   ✅ SendSmsJob parameters correct
-   ✅ Null safety implemented
-   🔧 **CRITICAL BUGS FIXED (2 instances):** Changed `requested_by` to `user` relationship
    -   Line 163: Fixed `$record->disbursement_voucher->requested_by` → `$record->disbursement_voucher->user`
    -   Line 285: Fixed same relationship issue
    -   Fixed eager loading from `requested_by.employee_information` to `user.employee_information`
-   ✅ Message variables properly constructed (tracking_number, officer_name, remarks with HTML stripped)

---

## 🛠 Diagnostic SMS API (manual verification)

### 9. SMS Diagnostic Controller (1 instance)

**File:** `app/Http/Controllers/Api/SmsTestController.php`

| Line | SMS Type        | Context | Description                                                                                |
| ---- | --------------- | ------- | ------------------------------------------------------------------------------------------ |
| ~50  | Diagnostic send | Dynamic | API endpoint for manual verification of the SMS pipeline (queued send + direct-send paths) |

**Routes** (registered in `routes/api.php:27`):

- `POST /api/sms/send` — queues via `SendSmsJob`
- `POST /api/sms/test-direct` — bypasses the queue and calls Semaphore synchronously (used for live verification)
- `GET  /api/sms/log/{id}` — fetch one `sms_logs` row
- `GET  /api/sms/logs` — recent rows, filterable by `phone`, `status`, `context`
- `GET  /api/sms/stats` — success rate / counts
- `GET  /api/sms/provider` — current provider info
- `POST /api/sms/format-phone` — preview the provider's phone-number formatting

**Real-world example call** (verified 2026-04-29, message_id `XXXXXXXXX`):

```bash
curl -X POST http://sksusearch.test/api/sms/test-direct \
  -H "Content-Type: application/json" \
  --data '{"phone":"09XXXXXXXXX","message":"SKSUSEARCH SMS deploy verification."}'
```

**Status:** ✅ Active — diagnostic use only.

---

## 📊 SMS Contexts Summary

| Context                               | Status                   | File                              | Description                 |
| ------------------------------------- | ------------------------ | --------------------------------- | --------------------------- |
| `travel_order_type_converted`         | ✅ Live                  | TravelOrdersToSignView.php        | Travel order converted      |
| `travel_order_approved`               | ✅ Live                  | TravelOrdersToSignView.php        | Travel order approved       |
| `travel_order_rejected`               | ✅ Live                  | TravelOrdersToSignView.php        | Travel order rejected       |
| `travel_order_signatory_notification` | ✅ Live                  | TravelOrdersCreate.php            | Notify signatory            |
| `vehicle_changed`                     | ✅ Live                  | RequestVehicleShow.php            | Vehicle changed             |
| `driver_changed`                      | ✅ Live                  | RequestVehicleShow.php            | Driver changed              |
| `vehicle_driver_confirmed`            | ✅ Live                  | RequestVehicleShow.php            | Vehicle/driver confirmed    |
| `petty_cash_voucher_liquidated`       | ✅ Live                  | PettyCashVouchersIndex.php        | Petty cash liquidated       |
| `petty_cash_voucher_issued`           | ✅ Live                  | PettyCashVouchersCreate.php       | Petty cash issued           |
| `FMR`                                 | ✅ Live                  | CashAdvanceReminders.php          | Formal Management Reminder  |
| `FMD`                                 | ✅ Live                  | CashAdvanceReminders.php          | Formal Management Demand    |
| `SCO`                                 | ✅ Live                  | CashAdvanceReminders.php          | Show Cause Order            |
| `ENDORSEMENT_PAYEE`                   | ✅ Live                  | CashAdvanceReminders.php          | Endorsement to Payee        |
| `ENDORSEMENT_AUDITOR`                 | ✅ Live                  | CashAdvanceReminders.php          | Endorsement to Auditor      |
| `FD`                                  | ✅ Live                  | CashAdvanceReminders.php          | Formal Demand from COA      |
| `FUND_ALLOCATION`                     | 📋 Reviewed (Not Active) | AllocateFunds.php                 | Regular fund allocation     |
| `FUND_ALLOCATION_161`                 | 📋 Reviewed (Not Active) | AllocateFunds.php                 | Fund 161 allocation         |
| `WFP_APPROVAL`                        | 📋 Reviewed (Not Active) | WfpSubmissions.php                | WFP approved                |
| `WFP_MODIFICATION`                    | 📋 Reviewed (Not Active) | WfpSubmissions.php                | WFP modification request    |
| `WFP_APPROVAL_Q1`                     | 📋 Reviewed (Not Active) | WfpSubmissionsQ1.php              | WFP Q1 approved             |
| `WFP_MODIFICATION_Q1`                 | 📋 Reviewed (Not Active) | WfpSubmissionsQ1.php              | WFP Q1 modification request |
| `disbursement_voucher_ready`          | ✅ Live                  | OfficeDashboardActions.php        | DV ready                    |
| `disbursement_voucher_submitted`      | ✅ Live                  | DisbursementVouchersCreate.php    | DV submitted                |
| `liquidation_report_returned`         | ✅ Live                  | OfficeLiquidationReportsIndex.php | Liquidation returned        |
| `liquidation_report_approved`         | ✅ Live                  | OfficeLiquidationReportsIndex.php | Liquidation approved        |

---

## 🔧 To Enable a Reviewed-but-Inactive SMS (WFP only)

The 18 production SMS in sections 1–5, 7, 8 are already live. The 6 WFP SMS in section 6 remain commented out pending accountant approval. To activate one of those:

1. **Open the WFP file** containing the SMS block (`AllocateFunds.php`, `WfpSubmissions.php`, or `WfpSubmissionsQ1.php`)
2. **Locate the SMS block** using the line numbers in section 6
3. **Uncomment the SMS code** by removing the `//` from each line — leave the fallback-number line commented; the live `contact_number` line is already the active one
4. **Verify with the diagnostic endpoint** (`POST /api/sms/test-direct`) using a real number you control
5. **Deploy** and monitor `sms_logs` for the WFP context strings (`FUND_ALLOCATION`, `WFP_APPROVAL`, etc.)

### Real-world example (matches the live pattern used by sections 1–5, 7, 8):

```php
// SMS notification — live recipient
if ($user->employee_information && !empty($user->employee_information->contact_number)) {
    SendSmsJob::dispatch(
        $user->employee_information->contact_number,  // live recipient from DB
        $message,
        'context_name',
        $user->id,
        Auth::id()
    );
}
```

---

## 🏆 Implementation Quality

### Cash Advance & WFP Implementations (Your Work)

✅ **100% Null Safety** - All data access protected
✅ **100% Error Handling** - Comprehensive try-catch blocks
✅ **100% Logging** - Success, warnings, and errors logged
✅ **0% Blocking** - No SMS failure blocks main actions
✅ **Independent** - Multiple SMS dispatches don't affect each other
✅ **Production Ready** - Uses actual phone numbers by default

### Other Implementations

⚠️ Basic null safety (checks employee_information and contact_number)
⚠️ No comprehensive error handling
✓  Uses the recipient's `contact_number` from `employee_information` (live in production)

---

## 📞 Recipient Phone Numbers

Live notifications dispatch to the recipient's `contact_number` from `employee_information` (loaded eagerly with the user). The hardcoded number `09273464891` only appears as a commented-out fallback inside the WFP SMS blocks (section 6, awaiting accountant approval). It is not used by any active dispatch.

For manual verification of the SMS pipeline against a phone you control, use the diagnostic endpoint described in section 9 (`POST /api/sms/test-direct`).

---

## 🚀 Deployment Checklist

**Production deploy date:** 2026-04-29

-   [x] Verify SMS service is configured and working — Semaphore configured (`SEMAPHORE_API_KEY`, sender `SKSUSEARCH`)
-   [x] Live verification SMS sent to a real phone — `09XXXXXXXXX` confirmed delivered (message_id `XXXXXXXXX`)
-   [x] Confirm message content with stakeholders
-   [x] Switch to recipient phone numbers from DB — done; all 18 active dispatches read `contact_number` from `employee_information`
-   [x] Document which SMS types are enabled — see SMS Contexts Summary above
-   [ ] Monitor `sms_logs` after deploy for failure spikes
-   [ ] Activate the 6 WFP SMS once accountant approves (section 6)

---

## 📁 Quick Reference - Files with SMS

| File                              | SMS Count | Status                              | Line Ranges                                 |
| --------------------------------- | --------- | ----------------------------------- | ------------------------------------------- |
| TravelOrdersToSignView.php        | 3         | ✅ Live                             | 68-75, 212-219, 270-277                     |
| TravelOrdersCreate.php            | 1         | ✅ Live                             | 236-243                                     |
| RequestVehicleShow.php            | 3         | ✅ Live                             | 487-494, 622-629, 752-759                   |
| PettyCashVouchersIndex.php        | 1         | ✅ Live                             | 113-120                                     |
| PettyCashVouchersCreate.php       | 1         | ✅ Live                             | 133-140                                     |
| CashAdvanceReminders.php          | 5         | ✅ Live                             | 146-220, 274-355, 408-491, 530-634, 720-805 |
| AllocateFunds.php                 | 2         | 📋 Reviewed (Not Active)            | 93-194, 239-340                             |
| WfpSubmissions.php                | 2         | 📋 Reviewed (Not Active)            | 130-248, 273-388                            |
| WfpSubmissionsQ1.php              | 2         | 📋 Reviewed (Not Active)            | 141-259, 284-399                            |
| DisbursementVouchersCreate.php    | 1         | ✅ Live                             | 930-937                                     |
| OfficeDashboardActions.php        | 1         | ✅ Live                             | 237-244                                     |
| OfficeLiquidationReportsIndex.php | 2         | ✅ Live                             | 165-172, 287-294                            |
| SmsTestController.php             | 1         | 🛠 Diagnostic API                   | ~50                                         |
| **Total**                         | **25**    | 18 Live + 6 Reviewed (Not Active) + 1 Diagnostic | -                          |

---

**Last Updated:** 2026-04-29 — doc rewritten to reflect live deployment (active SMS dispatch to recipient `contact_number` from DB, not a hardcoded number); line ranges refreshed to match current code.
**System Status:** 18 SMS LIVE in production (real recipients via `employee_information->contact_number`), 6 WFP SMS reviewed but commented-out (awaiting accountant approval), 1 diagnostic SMS API endpoint.
