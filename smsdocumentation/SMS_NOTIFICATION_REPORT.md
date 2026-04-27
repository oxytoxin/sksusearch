# SMS Notification System Report

**System:** SEARCH (SKSU)
**Date:** April 28, 2026
**Status:** Implemented and Tested
**Provider:** Semaphore

---

## Executive Summary

The SMS notification system has been fully implemented and tested on production server (https://sksusearch.com/). The system sends automated SMS notifications to users when specific actions occur in the SEARCH system.

| Category | Count |
|----------|-------|
| Total SMS Notifications | 25 |
| Active (Production Mode) | 18 |
| Reviewed (Awaiting Approval) | 6 |
| Test API | 1 |

---

## Phone Number Mode by Module

**ALL ACTIVE SMS NOTIFICATIONS USE ACTUAL PHONE NUMBERS**

| Module | Phone Number Mode | Phone Source |
|--------|-------------------|--------------|
| Travel Orders | **PRODUCTION** | `$applicant->employee_information->contact_number` |
| Motorpool | **PRODUCTION** | `$applicant->employee_information->contact_number` |
| Petty Cash | **PRODUCTION** | `$requisitioner->employee_information->contact_number` |
| Cash Advance Reminders | **PRODUCTION** | `$employee->contact_number` |
| Disbursement Vouchers | **PRODUCTION** | `$signatory->employee_information->contact_number` |
| Liquidation Reports | **PRODUCTION** | `$requestedBy->employee_information->contact_number` |
| Work & Financial Plan | NOT ACTIVE | Awaiting approval (commented out) |

### Important: All Data Uses Actual Values

All SMS notifications use **ACTUAL DATA** from the database - not test values:

| Data | Source | Mode |
|------|--------|------|
| Phone Number | `employee_information.contact_number` | **ACTUAL** |
| Liquidation Deadline | `ca_reminder_step.liquidation_period_end_date` | **ACTUAL** |
| Amount | `disbursement_voucher.total_sum` | **ACTUAL** |
| Check/ADA Number | `disbursement_voucher.cheque_number` | **ACTUAL** |
| DV Number | `disbursement_voucher.dv_number` | **ACTUAL** |
| Tracking Number | `travel_order.tracking_code`, `disbursement_voucher.tracking_number` | **ACTUAL** |
| FMR/FMD/SCO Numbers | User input during action | **ACTUAL** |
| Purpose | `disbursement_voucher_particulars.purpose` | **ACTUAL** |
| Vehicle/Driver Info | `vehicle.name`, `driver.name` | **ACTUAL** |
| Signatory Names | `user.name` | **ACTUAL** |

### Liquidation Deadline

The liquidation deadline in Cash Advance SMS uses the **ACTUAL deadline date** from the database:
- **Source:** `ca_reminder_step.liquidation_period_end_date`
- **Format:** "May 10, 2026" (actual date, not test 2-minute deadline)
- **Used in:** FMR, FMD, SCO messages

**Important:** When any SMS action is triggered, the SMS will be sent to the **actual employee phone number** stored in their profile. If the employee has no contact number, the SMS will be skipped (with a warning log).

---

## SMS Notifications - Detailed List with Realistic Examples

---

### 1. TRAVEL ORDERS MODULE

---

#### 1.1 Travel Order Converted

| Field | Details |
|-------|---------|
| **Trigger** | Signatory converts travel type (official to personal or vice versa) |
| **Recipient** | All applicants listed in the travel order |
| **Location** | Signatory → Travel Orders → Sign/Review → Convert Type |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Maria Santos submitted a travel order for a conference in Manila. The Vice President reviewed it and found that the conference is not directly related to her official duties, so he converted it from "Official Business" to "Personal Business."

**SMS Message Received by Dr. Maria Santos:**
```
Your travel on official business has been converted to personal by
Dr. Juan Dela Cruz (Vice President for Academic Affairs).
Please check your travel order with ref. no. TO-2026-00142 in the SEARCH system.
```

---

#### 1.2 Travel Order Approved

| Field | Details |
|-------|---------|
| **Trigger** | Signatory approves the travel order |
| **Recipient** | All applicants listed in the travel order |
| **Location** | Signatory → Travel Orders → Sign/Review → Approve |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Prof. Roberto Garcia and Prof. Ana Reyes submitted a joint travel order for a seminar in Davao City. The Dean reviewed and approved their travel.

**SMS Message Received by Prof. Roberto Garcia:**
```
Your travel order with ref. no. TO-2026-00158 has been approved by
Dr. Elena Fernandez (Dean, College of Education).
You may now proceed with your travel arrangements.
```

**SMS Message Received by Prof. Ana Reyes:**
```
Your travel order with ref. no. TO-2026-00158 has been approved by
Dr. Elena Fernandez (Dean, College of Education).
You may now proceed with your travel arrangements.
```

---

#### 1.3 Travel Order Rejected

| Field | Details |
|-------|---------|
| **Trigger** | Signatory rejects the travel order |
| **Recipient** | All applicants listed in the travel order |
| **Location** | Signatory → Travel Orders → Sign/Review → Reject |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Mr. Pedro Villanueva submitted a travel order for a training in Cebu. The Department Head rejected it because the training dates conflict with the final examination period.

**SMS Message Received by Mr. Pedro Villanueva:**
```
Your travel order with ref. no. TO-2026-00163 has been rejected by
Dr. Ricardo Bautista (Department Head, Computer Science).
Reason: Training dates conflict with final examination schedule.
Please reschedule to a later date.
Please check the SEARCH system for details.
```

---

#### 1.4 Travel Order Signatory Notification

| Field | Details |
|-------|---------|
| **Trigger** | New travel order is submitted for approval |
| **Recipient** | All assigned signatories |
| **Location** | Requisitioner → Travel Orders → Create → Submit |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Ms. Christine Morales (Faculty, College of Agriculture) submitted a travel order to attend a research conference. The system automatically notifies her Department Head and Dean.

**SMS Message Received by Dr. Antonio Cruz (Department Head):**
```
A travel order and its accompanying itinerary have been submitted to the
SEARCH system for your approval. Ref. No. TO-2026-00175.
Submitted by: Christine Morales
Please log in to review.
```

---

### 2. MOTORPOOL MODULE

---

#### 2.1 Vehicle Changed

| Field | Details |
|-------|---------|
| **Trigger** | Motorpool staff changes the assigned vehicle |
| **Recipient** | All applicants in the vehicle request |
| **Location** | Motorpool → Vehicle Requests → View → Change Vehicle |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Ramon Santos requested a van for a field trip. Initially, the Ford Hi-Ace (ABC-1234) was assigned, but due to maintenance issues, the Motorpool changed it to Toyota Coaster (XYZ-5678).

**SMS Message Received by Dr. Ramon Santos:**
```
The vehicle assigned to your request has been changed.
Previous Vehicle: Ford Hi-Ace (ABC-1234)
New Vehicle: Toyota Coaster (XYZ-5678)
Date of Travel: May 15, 2026
Departure: 6:00 AM
Please check the SEARCH system for updated details.
```

---

#### 2.2 Driver Changed

| Field | Details |
|-------|---------|
| **Trigger** | Motorpool staff changes the assigned driver |
| **Recipient** | All applicants in the vehicle request |
| **Location** | Motorpool → Vehicle Requests → View → Change Driver |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Prof. Linda Aquino has a scheduled travel to General Santos City. The originally assigned driver, Mr. Jose Ramos, called in sick. The Motorpool assigned Mr. Eduardo Mendoza instead.

**SMS Message Received by Prof. Linda Aquino:**
```
The driver assigned to your request has been changed.
Previous Driver: Jose Ramos
New Driver: Eduardo Mendoza
Vehicle: Mitsubishi Adventure (DEF-9012)
Date of Travel: May 20, 2026
Contact the Motorpool Office if you have concerns.
```

---

#### 2.3 Vehicle/Driver Confirmed

| Field | Details |
|-------|---------|
| **Trigger** | Motorpool confirms vehicle and driver assignment |
| **Recipient** | All applicants in the vehicle request |
| **Location** | Motorpool → Vehicle Requests → View → Confirm |
| **Status** | ACTIVE |

**Realistic Scenario:**
> A group of 5 faculty members requested a vehicle for an accreditation visit. The Motorpool has finalized the assignment and confirmed the booking.

**SMS Message Received by all 5 applicants:**
```
Your vehicle request has been confirmed.
Vehicle: Toyota Hi-Ace GL Grandia (GHI-3456)
Driver: Mario Dela Peña
Date of Travel: May 25, 2026
Departure Time: 5:00 AM
Pick-up Point: SKSU Main Gate
Please be ready 15 minutes before departure.
```

---

### 3. PETTY CASH MODULE

---

#### 3.1 Petty Cash Voucher Issued

| Field | Details |
|-------|---------|
| **Trigger** | Petty cash voucher is issued to requisitioner |
| **Recipient** | Requisitioner (person who requested) |
| **Location** | Petty Cash → Create/Issue Voucher |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Ms. Jennifer Castro (Administrative Staff) requested petty cash for office supplies. The Accounting Office approved and issued the petty cash voucher.

**SMS Message Received by Ms. Jennifer Castro:**
```
Petty cash voucher issued.
PCV Ref. No.: PCV-2026-00089
Amount: Php 2,500.00
Purpose: Purchase of office supplies (bond paper, ink, folders)
Please claim your petty cash at the Cashier's Office.
Liquidation Deadline: May 10, 2026
```

---

#### 3.2 Petty Cash Voucher Liquidated

| Field | Details |
|-------|---------|
| **Trigger** | Petty cash voucher has been liquidated |
| **Recipient** | Requisitioner (person who requested) |
| **Location** | Petty Cash → Liquidate |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Ms. Jennifer Castro submitted her receipts totaling Php 2,350.00. The remaining Php 150.00 was returned. The Accounting Office processed the liquidation.

**SMS Message Received by Ms. Jennifer Castro:**
```
Your petty cash with PCV ref. no. PCV-2026-00089 has been liquidated.
Amount Granted: Php 2,500.00
Amount Spent: Php 2,350.00
Amount Refunded: Php 150.00
Thank you for your timely liquidation.
```

---

### 4. CASH ADVANCE REMINDERS MODULE

---

#### 4.1 FMR (First Management Reminder)

| Field | Details |
|-------|---------|
| **Trigger** | FMR is issued for unliquidated cash advance |
| **Recipient** | Payee (person with unliquidated cash advance) |
| **Location** | Cash Advance Reminders → Issue FMR |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Michael Tan received a cash advance of Php 50,000.00 for a training activity last March. It has been 45 days and he has not yet submitted his liquidation report. The Accounting Office issues the first reminder.

**SMS Message Received by Dr. Michael Tan:**
```
FMR No. FMR-2026-00023 has been sent to you for your unliquidated cash advance.
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
Date Granted: March 15, 2026
Days Overdue: 45 days
Please liquidate your cash advance within 5 working days to avoid FMD issuance.
```

---

#### 4.2 FMD (Formal Management Demand)

| Field | Details |
|-------|---------|
| **Trigger** | FMD is issued (escalation from FMR) |
| **Recipient** | Payee (person with unliquidated cash advance) |
| **Location** | Cash Advance Reminders → Issue FMD |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Michael Tan still has not liquidated after the FMR. The Accounting Office escalates to FMD.

**SMS Message Received by Dr. Michael Tan:**
```
FMD No. FMD-2026-00018 has been sent to you for your unliquidated cash advance.
FMR No. FMR-2026-00023 was earlier sent to you on April 30, 2026.
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
Days Overdue: 52 days
This is a FORMAL DEMAND. Please liquidate immediately to avoid Show Cause Order.
```

---

#### 4.3 SCO (Show Cause Order)

| Field | Details |
|-------|---------|
| **Trigger** | Show Cause Order memorandum is issued |
| **Recipient** | Payee (person with unliquidated cash advance) |
| **Location** | Cash Advance Reminders → Issue SCO |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Michael Tan still has not responded. The University President issues a Show Cause Order memorandum.

**SMS Message Received by Dr. Michael Tan:**
```
SHOW CAUSE ORDER
Memorandum No. SCO-2026-00012 has been sent to you, ordering you to show cause
why your unliquidated cash advance should not be endorsed to the Commission on Audit.
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
Previous Notices: FMR-2026-00023, FMD-2026-00018
You have 3 WORKING DAYS to respond in writing to the Office of the President.
```

---

#### 4.4 Endorsement Notice (2 SMS)

| Field | Details |
|-------|---------|
| **Trigger** | Case is endorsed to Commission on Audit |
| **Recipient** | Payee AND COA Auditor (2 separate SMS) |
| **Location** | Cash Advance Reminders → Endorse to COA |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Dr. Michael Tan failed to respond to the Show Cause Order. The case is now endorsed to COA for Formal Demand.

**SMS Message #1 - Received by Dr. Michael Tan (Payee):**
```
NOTICE OF ENDORSEMENT
Your unliquidated cash advance has been endorsed to the Commission on Audit
for issuance of Formal Demand.
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
Days Overdue: 75 days
The COA Resident Auditor has been officially notified.
Please coordinate with the Auditor's Office immediately.
```

**SMS Message #2 - Received by Atty. Rosa Villanueva (COA Auditor):**
```
ENDORSEMENT FOR FORMAL DEMAND
An unliquidated cash advance has been endorsed to your office.
Payee: Dr. Michael Tan (Faculty, College of Business)
Employee ID: 2018-0456
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
Previous notices issued: FMR, FMD, SCO (all unheeded)
Please process Formal Demand accordingly.
```

---

#### 4.5 FD (Formal Demand from COA)

| Field | Details |
|-------|---------|
| **Trigger** | Formal Demand is served by COA |
| **Recipient** | Payee (person with unliquidated cash advance) |
| **Location** | Cash Advance Reminders → Issue Formal Demand |
| **Status** | ACTIVE |

**Realistic Scenario:**
> The COA Resident Auditor officially serves the Formal Demand to Dr. Michael Tan.

**SMS Message Received by Dr. Michael Tan:**
```
FORMAL DEMAND - COMMISSION ON AUDIT
The Commission on Audit has electronically served your Formal Demand
for unliquidated cash advance.
FD No.: FD-2026-00008
DV/Check No.: 2026-03-0456
Amount: Php 50,000.00
IMPORTANT: Failure to settle may result in:
- Salary deduction
- Disallowance in audit
- Legal action
Contact the COA Office immediately.
```

---

### 5. DISBURSEMENT VOUCHERS MODULE

---

#### 5.1 DV Submitted for Approval

| Field | Details |
|-------|---------|
| **Trigger** | Disbursement voucher is submitted for approval |
| **Recipient** | Assigned signatory |
| **Location** | Requisitioner → Disbursement Vouchers → Create → Submit |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Ms. Karen Lim (Budget Officer) prepared a disbursement voucher for the payment of electricity bill. The DV is submitted to the Chief Accountant for approval.

**SMS Message Received by Mr. Eduardo Santos (Chief Accountant):**
```
A DV has been submitted to the SEARCH system for your approval.
DV Ref. No.: DV-2026-05-0234
Prepared by: Karen Lim (Budget Officer)
Payee: Sultan Kudarat Electric Cooperative (SUKELCO)
Amount: Php 487,562.50
Purpose: Payment of electricity bill - April 2026
Please log in to review and sign.
```

---

#### 5.2 DV Ready for Disbursement

| Field | Details |
|-------|---------|
| **Trigger** | DV is ready with check/ADA number |
| **Recipient** | Requisitioner/Payee |
| **Location** | Office Dashboard → Mark as Ready |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Prof. Diana Cruz submitted a reimbursement for her travel expenses. All signatories have approved, and the Cashier has prepared the check.

**SMS Message Received by Prof. Diana Cruz:**
```
Your DV with ref. no. DV-2026-04-0189 is ready for disbursement.
Check Number: 0012456789
Amount: Php 15,750.00
Purpose: Reimbursement of travel expenses (Manila trip, April 10-12, 2026)
Please proceed to the Cashier's Office to claim your check.
Bring valid ID and sign the voucher upon claiming.
```

---

### 6. LIQUIDATION REPORTS MODULE

---

#### 6.1 Liquidation Report Returned

| Field | Details |
|-------|---------|
| **Trigger** | Liquidation report is returned with remarks |
| **Recipient** | Requisitioner |
| **Location** | Office → Liquidation Reports → Return |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Mr. Bryan Reyes submitted his liquidation report for a cash advance. The Accounting Office found missing receipts and returned it for correction.

**SMS Message Received by Mr. Bryan Reyes:**
```
Your LR with ref. no. LR-2026-00156 has been returned by
Ms. Gloria Mendez (Accounting Staff).
Remarks: Missing official receipts for meals (April 15-16).
Please attach OR or affidavit of loss.
Please review and resubmit with the necessary corrections within 3 days.
```

---

#### 6.2 Liquidation Report Approved

| Field | Details |
|-------|---------|
| **Trigger** | Liquidation report is approved |
| **Recipient** | Requisitioner |
| **Location** | Office → Liquidation Reports → Approve |
| **Status** | ACTIVE |

**Realistic Scenario:**
> Mr. Bryan Reyes resubmitted his liquidation report with complete documents. The Accounting Office approved it.

**SMS Message Received by Mr. Bryan Reyes:**
```
Your LR with ref. no. LR-2026-00156 has been approved.
Cash Advance Amount: Php 25,000.00
Total Liquidated: Php 24,850.00
Refund Due: Php 150.00
Your liquidation is now complete. Thank you for your compliance.
```

---

### 7. WORK & FINANCIAL PLAN (WFP) MODULE

**Status:** REVIEWED - Awaiting Accountant Approval Before Activation

---

#### 7.1 Fund Allocation

| Field | Details |
|-------|---------|
| **Trigger** | Fund is allocated to cost center |
| **Recipient** | Cost Center Head |
| **Location** | WFP → Allocate Funds |
| **Status** | AWAITING APPROVAL |

**Realistic Scenario:**
> The Budget Office allocates Php 2,500,000.00 to the College of Engineering for Q2 operations.

**SMS Message to be Received by Dean Engr. Roberto Aquino:**
```
FUND ALLOCATION NOTICE
You have been allocated a fund of Php 2,500,000.00 under:
Fund: Fund 101 (General Fund)
MFO: Higher Education Services
Cost Center: College of Engineering
Fiscal Year: 2026, Q2
Please log in to SEARCH to program your expenditures.
Deadline for WFP submission: May 15, 2026
```

---

#### 7.2 WFP Approved

| Field | Details |
|-------|---------|
| **Trigger** | WFP submission is approved by accountant |
| **Recipient** | Cost Center Head |
| **Location** | WFP → Submissions → Approve |
| **Status** | AWAITING APPROVAL |

**Realistic Scenario:**
> The Chief Accountant reviewed and approved the WFP submission of the College of Agriculture.

**SMS Message to be Received by Dean Dr. Felipe Gonzales:**
```
WFP APPROVED
Your expenditure programming for College of Agriculture has been approved.
Fund: Fund 101
Quarter: Q2 2026
Amount Programmed: Php 1,850,000.00
Breakdown:
- MOOE: Php 1,200,000.00
- Capital Outlay: Php 650,000.00
You may now proceed with your planned activities and procurements.
```

---

#### 7.3 WFP Modification Required

| Field | Details |
|-------|---------|
| **Trigger** | WFP is returned for modification |
| **Recipient** | Cost Center Head |
| **Location** | WFP → Submissions → Return for Modification |
| **Status** | AWAITING APPROVAL |

**Realistic Scenario:**
> The Chief Accountant found issues with the WFP submission of the Graduate School and returned it for correction.

**SMS Message to be Received by Dr. Teresita Luna (Graduate School Director):**
```
WFP RETURNED FOR MODIFICATION
Your expenditure programming for Graduate School has been returned.
Remarks from Accountant:
"Capital outlay amount exceeds allocation by Php 150,000.
Please revise equipment list or realign from MOOE."
Original Submission: Php 980,000.00
Please review and resubmit your WFP in the SEARCH system.
Deadline: May 10, 2026
```

---

## Summary Table

| Module | SMS Count | Status | Phone Mode | Recipients |
|--------|-----------|--------|------------|------------|
| Travel Orders | 4 | ACTIVE | **PRODUCTION** | Applicants, Signatories |
| Motorpool | 3 | ACTIVE | **PRODUCTION** | Applicants |
| Petty Cash | 2 | ACTIVE | **PRODUCTION** | Requisitioner |
| Cash Advance Reminders | 5 | ACTIVE | **PRODUCTION** | Payee, COA Auditor |
| Disbursement Vouchers | 2 | ACTIVE | **PRODUCTION** | Signatory, Requisitioner |
| Liquidation Reports | 2 | ACTIVE | **PRODUCTION** | Requisitioner |
| Work & Financial Plan | 6 | AWAITING APPROVAL | Not Active | Cost Center Heads |
| **Total** | **24** | **18 PRODUCTION** | | |

**Note:** All 18 active SMS notifications use **ACTUAL employee phone numbers** and **ACTUAL data** from the database.

---

## System Verification

| Test | Date | Result |
|------|------|--------|
| SMS Provider Connection | April 28, 2026 | PASSED |
| Phone Number Formatting | April 28, 2026 | PASSED |
| SMS Delivery Test | April 28, 2026 | PASSED |
| Test SMS Received | April 28, 2026 | PASSED |

**Test Details:**
- Phone Tested: 09366303145
- Message Sent: "Test SMS from SEARCH System"
- Provider: Semaphore
- Sender ID: SKSUSEARCH
- Result: Message delivered successfully

---

## Technical Information

| Setting | Value |
|---------|-------|
| SMS Provider | Semaphore |
| Sender Name | SKSUSEARCH |
| Production URL | https://sksusearch.com |
| Queue System | Database (background processing) |
| Auto-Retry | 3 attempts (1 min, 5 min, 15 min intervals) |
| Phone Format | Accepts 09XX, +639XX, 639XX formats |

---

## Requirements

For SMS notifications to work:
1. Employee must have mobile number in their profile
2. Mobile number format: 09XXXXXXXXX or +639XXXXXXXXX
3. Queue worker must be running on server

---

## Current Deployment Status

| Item | Status |
|------|--------|
| SMS Infrastructure | **OPERATIONAL** |
| SMS Provider | Semaphore (SKSUSEARCH) |
| 18 Active Notifications | **PRODUCTION MODE** (using actual phone numbers) |
| Data Source | **ACTUAL** (real employee data, real deadlines) |
| Liquidation Deadline | **ACTUAL** (not test 2-minute deadline) |
| 6 WFP Notifications | Awaiting accountant approval |

### What This Means

When any of the 18 active SMS notifications are triggered:
1. **SMS goes to the ACTUAL employee phone number** in their profile
2. **All dates, amounts, and reference numbers are REAL** from the database
3. **Liquidation deadlines are ACTUAL** dates (not test values)

---

**Prepared by:** ICT Development Team
**Institution:** Sultan Kudarat State University
**System:** SEARCH
**Document Version:** 2.1
**Date:** April 28, 2026
