# Cash Advance Aging — What it is & how it works

## What "aging" means
"Aging" tracks **how long each unliquidated cash advance has been overdue.** When an employee receives a cash advance (money released via cheque/ADA), they must **liquidate** it (submit receipts/refund) by a deadline. If they don't, the advance is "outstanding" and gets **aged** — bucketed by how many days past the deadline it is.

This is a **COA (Commission on Audit) compliance requirement**: agencies must monitor and collect unliquidated cash advances, and unliquidated CAs are one of the most common audit findings.

## The official format (from the client)
The authoritative format is the client's COA **"Aging of Cash Advances"** schedule (provided as an Excel exported from QuickBooks). The in-app report now matches it:

**Columns:** Ref. · Name · Date (granted) · Reference (cheque/ADA no.) · Account (GL code) · Particulars · Amount · Fund · No. of Days · then **6 aging buckets**.

**6 buckets** (the amount appears in the column matching the row's age):
| Bucket | Days overdue |
|---|---|
| 30 days or less *(Current)* | 0–30 |
| 31 – 90 days | 31–90 |
| 91 – 365 days | 91–365 |
| Over 1 year | 366–730 |
| Over 2 years | 731–1095 |
| 3 years and above | 1096+ |

## How the days are calculated
```
No. of days overdue = max( (As-of date) − liquidation_period_end_date , 0 )
```
- `liquidation_period_end_date` lives on `ca_reminder_steps` and is set when the cashier issues the cheque (= activity end date + N days: 30 travel / 20 activities / 5 payroll, etc.).
- "As-of date" defaults to today; the user can pick any date in the report.

## Where the data comes from
| Report column | Source |
|---|---|
| Name | `disbursement_voucher.user` (or `payee`) |
| Date (granted) | `disbursement_voucher.cheque_number_added_at` |
| Reference | `disbursement_voucher.cheque_number` |
| **Account (GL)** | **derived** from voucher subtype via `config/coa_accounts.php` |
| Particulars | first particular's `purpose` |
| Amount | `disbursement_voucher.total_sum` |
| Fund | `disbursement_voucher.fund_cluster.name` (101/161/164…) |
| No. of days / bucket | computed from `liquidation_period_end_date` |

### The Account (GL) mapping
SEARCH does not store the COA GL code, so it's **derived from the voucher subtype** (editable in `config/coa_accounts.php` — no code change needed):
- Travel CAs (Local/Foreign/Students/legacy) → **1914000 Advances to Officers and Employees**
- Activity/Program/Project → **1911000 Advances for Operating Expenses**
- Payroll → **1912000 Advances for Payroll**
- Special Disbursing Officer → **1913000 Advances to Special Disbursing Officer**
- anything else → default **1911000**

## Which cash advances appear
A cash advance shows in the report when **all** are true:
- It's a cash advance (`voucher_type_id = 1`, excluding Petty Cash Fund subtype 69)
- A cheque/ADA was issued (`cheque_number` is set)
- It is **not yet liquidated** (no active liquidation report)
- Its `liquidation_period_end_date` is **before** the as-of date (i.e. overdue)

> **Scope:** the report ages **cash advances tracked in SEARCH only.** It does **not** include the old 2015–2022 cash advances that live in QuickBooks (those would need a separate one-time import — a future phase).

## How aging ties into the escalation pipeline
Once overdue, the system escalates demands automatically (cron `cash-advance:check-reminders`):
**FMR → FMD → SCO → Endorsement → FD** (each step notifies the payee via SMS + in-app notification). The aging report is the oversight view; the escalation is the action. Timing is in `config/cash_advance.php` (production days vs demo-mode minutes).

## Using the report
- Route: the Cash Advance Aging report (accessible to Accountant / Finance / President / Auditor).
- Filters: **As-of date**, **Fund cluster**, **Aging bucket** (click a bucket card or button), **Search** (name/DV/cheque/office).
- **Print** → COA-style A4 landscape report with letterhead, "All Funds"/specific fund, grand totals.
- **Export Excel** → full dataset (uncapped) matching the COA columns + 6 buckets + grand-total row. On-screen is capped at 1,000 rows for speed (export for everything).

## Key files
| Purpose | File |
|---|---|
| Report component | `app/Http/Livewire/Reports/CashAdvanceAging.php` |
| Report view (screen + print) | `resources/views/livewire/reports/cash-advance-aging.blade.php` |
| Excel export | `app/Exports/CashAdvanceAgingExport.php` |
| GL account mapping | `config/coa_accounts.php` |
| Fund clusters | `app/Models/FundCluster.php`, `database/seeders/FundClusterSeeder.php` |
| Aging anchor / escalation | `app/Models/CaReminderStep.php`, `app/Console/Commands/CheckCashAdvanceReminders.php`, `config/cash_advance.php` |

## Notes / open items for accounting
- Confirm the `voucher_subtype → GL account` mapping in `config/coa_accounts.php`.
- Confirm whether fund sub-letters `164A/164G` are needed (SEARCH currently uses `101/161/163/164`).
- Historical (pre-SEARCH) cash advances are out of scope unless imported.
