# SMS Notification Triggers — SEARCH System

**Purpose:** This document lists every action in the SEARCH system that triggers an SMS notification, the recipient, and the exact message content sent.

**Last reviewed:** 2026-05-20
**System health at review:** 100% delivery rate (68/68 sent, 0 failed)

---

## Table of Contents

1. [Disbursement Voucher (DV)](#1-disbursement-voucher-dv)
2. [Travel Order (TO)](#2-travel-order-to)
3. [Vehicle / Motorpool](#3-vehicle--motorpool)
4. [Cash Advance / Liquidation Reminders](#4-cash-advance--liquidation-reminders-automated-cron)
5. [Waiting Periods](#5-waiting-periods-cash-advance-reminders)
6. [SMS Skip Rules](#6-sms-skip-rules)
7. [Provider Configuration](#7-sms-provider-configuration)
8. [Delivery Status Lifecycle](#8-sms-delivery-status-lifecycle-semaphore)
9. [Summary](#9-summary)
10. [Source File References](#10-source-file-references)

---

## 1. Disbursement Voucher (DV)

| # | Trigger Action | Performed By | SMS Recipient |
|---|---|---|---|
| 1 | DV submitted to signatory | Requisitioner | Next Signatory |
| 2 | DV returned (Signatory) | Signatory at step 4000 | DV Requisitioner |
| 3 | DV returned (ICU) | ICU Verifier at step 6000 | DV Requisitioner |
| 4 | DV ready for disbursement | Cashier (enters Cheque/ADA at step 17000) | DV Requisitioner |
| 5 | DV Forward / Approve | Signatory (any step) | DV Requisitioner |

### Messages

**#1 — context: `disbursement_voucher_submitted`**
> A DV has been submitted to the SEARCH system by {requestor} for your approval.

**#2 — context: `disbursement_voucher_returned`** (Signatory return at step 4000)
> Your DV with ref. no. {tracking_no} has been returned by {officer} with the following remarks: "{remarks}". Please retrieve your documents immediately.

**#3 — context: `disbursement_voucher_returned`** (ICU return at step 6000)
> Your DV with ref. no. {tracking_no} has been returned by {officer} with the following remarks: "{remarks}". Please retrieve your documents immediately.

**#4 — context: `disbursement_voucher_ready`**
> Your DV with ref. no. {tracking_no} is ready for disbursement with check/ADA number {cheque_no}.

**#5 — context: `disbursement_voucher_forwarded`**
> Your DV with ref. no. {tracking_no} has been approved by [OIC ]{officer} and forwarded to {next_recipient}.

> The "OIC " prefix is included only when the approver acts as Officer-in-Charge.

---

## 2. Travel Order (TO)

| # | Trigger Action | Performed By | SMS Recipient |
|---|---|---|---|
| 6 | Travel Order submitted | Requisitioner | All Assigned Signatories |
| 7 | Travel Order approved | Signatory | Requisitioner |

### Messages

**#6 — context: `travel_order_signatory_notification`**
> A travel order and its accompanying itinerary have been submitted to the SEARCH system by {requestor} for your approval. Tracking Code: {tracking_code}

**#7 — context: `travel_order_approved`**
> Your travel order with ref. no. {tracking_no} has been approved by {signatory}.

---

## 3. Vehicle / Motorpool

| # | Trigger Action | Performed By | SMS Recipient |
|---|---|---|---|
| 8 | Vehicle & Driver Confirmed | Motorpool Officer | Requisitioner |

### Messages

**#8 — context: `vehicle_driver_confirmed`**
> Your vehicle request with TO number {to_no} to {destination} on {date} has been approved. Your vehicle is {vehicle} with plate no. {plate} and your driver is {driver}. Emergencies and other unfavorable circumstances may result in changes so closely coordinate with the General Services Office.

---

## 4. Cash Advance / Liquidation Reminders (Automated Cron)

| #  | Trigger Action | Performed By | SMS Recipient |
|----|---|---|---|
| 9  | First Memo Reminder (FMR) | System Cron | Cash Advance Recipient |
| 10 | Final Memo Demand (FMD)   | System Cron | Cash Advance Recipient |
| 11 | Show Cause Order (SCO)    | System Cron | Cash Advance Recipient |

### Messages

**#9 — context: `FMR`** (First Memo Reminder)
> Payee is reminded of an unliquidated cash advance past the liquidation deadline.

**#10 — context: `FMD`** (Final Memo Demand)
> FMD No. {fmd_no} has been sent to you for your unliquidated cash advance disbursed via check/ADA number {cheque_no} amounting to ₱{amount} for the following purpose: "{purpose}". Your liquidation deadline was on {deadline}. FMR No. {fmr_no} was earlier sent to you as a reminder.

**#11 — context: `SCO`** (Show Cause Order)
> Final notice before administrative action — payee must explain failure to liquidate.

---

## 5. Waiting Periods (Cash Advance Reminders)

| Phase | Production | Demo Mode |
|---|---|---|
| Liquidation deadline → FMR | Immediate after deadline | — |
| FMR → FMD | 15 days | 2 minutes |
| FMD → SCO | 30 days | 2 minutes |
| SCO → End | 30 days | 2 minutes |

*Demo mode is toggled via `CA_REMINDER_DEMO_MODE` in `.env`.*

---

## 6. SMS Skip Rules

An SMS is **silently NOT sent** when any of the following is true:

| # | Skip Condition |
|---|---|
| 1 | Recipient user record does not exist |
| 2 | Recipient user has no `employee_information` record |
| 3 | Recipient's `contact_number` field is empty or NULL |
| 4 | Phone number fails format validation |
| 5 | Number is on Semaphore's internal blacklist |
| 6 | Semaphore account has insufficient credit balance |

> Note: Skips are silent — no error is shown to the user performing the action. Always verify recipient contact numbers are saved before relying on SMS delivery.

---

## 7. SMS Provider Configuration

| Parameter | Value |
|---|---|
| Default Provider | Semaphore |
| Sender Name | SKSUSEARCH |
| Account Owner | ictadminx@sksu.edu.ph (Sultan Kudarat State University) |
| Backup Provider | PhilSMS (configured but inactive) |
| Queue Driver | Redis (`default` queue) |
| Workers | 16 Supervisor-managed processes (8 main + 8 proxy) |

---

## 8. SMS Delivery Status Lifecycle (Semaphore)

| Status | Meaning |
|---|---|
| Pending | Request accepted by Semaphore, not yet sent to telco |
| Sent | Handed off to telco (Globe / Smart / etc.) |
| Delivered | Confirmed received on recipient phone |
| Failed | Could not be delivered — credit refunded |
| Refunded | Telco rejected — credit refunded |

---

## 9. Summary

| Metric | Value |
|---|---|
| Total SMS triggers in the system | 12 |
| Modules covered | DV (5), Travel Order (2), Vehicle (1), Cash Advance Reminders (3), plus DV Submitted |
| Approval/Forward action | Sends SMS to requisitioner (`disbursement_voucher_forwarded`) |
| Latest health check | 100% delivery success rate (68/68 sent, 0 failed) |

---

## 10. Source File References

All paths are relative to `app/Http/Livewire/` unless noted otherwise.

| Trigger | File | Lines |
|---|---|---|
| DV Submitted | `Requisitioner/DisbursementVouchers/DisbursementVouchersCreate.php` | — |
| DV Returned (Signatory) | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php` | 163–186 |
| DV Returned (ICU) | `Offices/Traits/OfficeDashboardActions.php` | 321–341 |
| DV Ready (Cheque/ADA) | `Offices/Traits/OfficeDashboardActions.php` | 420–437 |
| DV Returned (OIC) | `Oic/OicOfficeDisbursementVouchers.php` | 108–131 |
| DV Returned (OIC Signatory) | `Oic/OicSignatoryDisbursementVouchers.php` | 148–171 |
| DV Returned (Office) | `Offices/OfficeDisbursementVouchersIndex.php` | 184–207 |
| Travel Order Submitted | `Requisitioner/TravelOrders/TravelOrdersCreate.php` | — |
| Travel Order Approved | `Signatory/TravelOrders/TravelOrdersToSignView.php` | — |
| Vehicle Driver Confirmed | `Requisitioner/Motorpool/RequestVehicleShow.php` | — |
| Cash Advance Reminders | `app/Console/Commands/CheckCashAdvanceReminders.php` | — |
| Forward / Approve (Office) | `Offices/Traits/OfficeDashboardActions.php` | 624–695 |
| Forward / Approve (Signatory) | `Signatory/DisbursementVouchers/DisbursementVouchersIndex.php` | 88–144 |
| SMS Job Handler | `app/Jobs/SendSmsJob.php` | — |
| SMS Service | `app/Services/SmsService.php` | — |
| Semaphore Provider | `app/Services/Sms/Providers/SemaphoreProvider.php` | — |
| SMS Log Model | `app/Models/SmsLog.php` | — |
