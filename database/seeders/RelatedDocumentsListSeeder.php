<?php

namespace Database\Seeders;

use App\Models\RelatedDocumentsList;
use Illuminate\Database\Seeder;

class RelatedDocumentsListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getDocuments() as $key => $subtype) {
            RelatedDocumentsList::create([
                'voucher_sub_type_id' => $key + 1,
                'documents' => $subtype['documents'],
            ]);
        }
    }

    private function getDocuments()
    {
        return [
            [
                'category' => 'Cash Advances',
                'subcategory' => 'Local Travel',
                'type' => 'Disbursements',
                'documents' => [
                    'Travel Order',
                    'Request Letter',
                    'Other supporting documents',
                ],
            ],
            [
                'category' => 'Cash Advances',
                'subcategory' => 'Foreign Travel',
                'type' => 'Disbursements',
                'documents' => [
                    'Travel Order',
                    'Request Letter',
                    'Other supporting documents',
                ],
            ],
            [
                'category' => 'Cash Advances',
                'subcategory' => 'Activity, Program, Project, ETC.',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Cash Advances',
                'subcategory' => 'Payroll',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Cash Advances',
                'subcategory' => 'Special Disbursing Officer',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Reimbursements',
                'subcategory' => 'Local Travel',
                'type' => 'Disbursements',
                'documents' => [
                    'Travel Order',
                    'Request Letter',
                    'Other supporting documents',
                ],
            ],
            [
                'category' => 'Reimbursements',
                'subcategory' => 'Foreign Travel',
                'type' => 'Disbursements',
                'documents' => [
                    'Travel Order',
                    'Request Letter',
                    'Other supporting documents',
                ],
            ],
            [
                'category' => 'Reimbursements',
                'subcategory' => 'Activity, Program, Project, ETC.',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Reimbursements',
                'subcategory' => 'Supplies/Materials',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Individual Compensation for Salary/Wage (COS/JO)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Certified true copy of duly approved Appointment',
                    'Assignment Order, if applicable',
                    'Certified true copy of Oath of Office',
                    'Certificate of Assumption',
                    'Statement of Assets, Liabilities and Net Worth',
                    'Approved DTR',
                    'Bureu of Internal Revenue (BIR) withholding certificate (Forms 1902 and 2305)',
                    'Payroll Information on New Employee (PINE) (for agencies with computerized payroll systems)',
                    'Authority from the claimant and identification documents, if claimed by person other than the payee',
                    'Clearace from money, property and legal accountabilities from the previous office',
                    'Certified true copy of pre-audited disbursement voucher of last salary from previous agency and/or Certification by the Chief Accountant of last salary received from previous office duly verified by the assigned auditor thereat',
                    'BIR Form 2316 (Certificate of Compensation Payment/Tax Withheld)',
                    'Certificate of Available Leave Credits',
                    'Service Record',
                ],
            ],
            [
                'category' => 'Payroll Compensation for Salaries/Wages (COS/JO)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Certified true copy of duly approved Appointment',
                    'Assignment Order, if applicable',
                    'Certified true copy of Oath of Office',
                    'Certificate of Assumption',
                    'Statement of Assets, Liabilities and Net Worth',
                    'Approved DTR',
                    'Bureu of Internal Revenue (BIR) withholding certificate (Forms 1902 and 2305)',
                    'Payroll Information on New Employee (PINE) (for agencies with computerized payroll systems)',
                    'Authority from the claimant and identification documents, if claimed by person other than the payee',
                    'Clearace from money, property and legal accountabilities from the previous office',
                    'Certified true copy of pre-audited disbursement voucher of last salary from previous agency and/or Certification by the Chief Accountant of last salary received from previous office duly verified by the assigned auditor thereat',
                    'BIR Form 2316 (Certificate of Compensation Payment/Tax Withheld)',
                    'Certificate of Available Leave Credits',
                    'Service Record',
                ],
            ],
            [
                'category' => 'Utilities, Fuel, Internet, Telephone, Etc.',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Statement of Account/Bill (for pre-audit purposes)',
                    'Invoice/Official Receipt or machine validated statement of account/bill (for post-audit purposes)',
                    'Certification by Agency Head or his authorized representatives that all National Direct Dial (NDD), National Operator Assisted Calls and International Operator Assisted Calls are official calls*For Telephone/Communication Services Only',
                ],
            ],
            [
                'category' => 'Payment to Contractors of Infrastructure Projects',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Letter request from contractors for advance/progress/final payment or for substitution in case of release of retention money',
                    "
                    Infrastructure\n
                    ○    Statement of Work Accomplished/Progress Billing\n
                    >○    Inspection Report by the Agency's Authorized Engineer\n
                    >○    Results of Test Analysis, if applicable\n
                    >○    Statement of Time Elapsed\n
                    >○    Monthly Certificate of Payment\n
                    >○    Contractor's Affidavit on payment of laborers and materials\n
                    >○    Pictures, before, during and after construction of items of work especially the embedded items\n
                    >○    Photocopy of vouchers of all previous payments\n
                    >○    Certificate of completion
                  ",
                    "
                    Direct Contracting\n
                    ○    Copy of letter to selected manufacturer/supplier/distributor to submit a price quotation and conditions of sale\n
                    >○    Certificate of Exclusive Distributorship issued by the principal under oath and authenticated by the embassy/consulate nearest the place of the principal, in case of foreign supplier\n
                    >○    Certification from the agency authorized official that there are no sub-dealers selling at lower prices and for which no suitable substitute can be obtained at more advantageous terms to the government\n
                    >○    Certification of the BAC in case of procurement of critical plant components and/or to maintain certain standards
                    ",
                    'Study/survey done to determine that there are no sub-dealers selling at lower prices and for which no suitable substitute can be obtained at more advantageous terms of government',
                    'Such other documents peculiar to the contract and/or to the mode of procurement and considered necessary in the auditorial review and in technical evaluation thereof',
                    "
                    Negotiated Procurement\n
                        In cases of two failed biddings, emergency cases, take-over of contract and small value procurement\n
                       -  Price quotation/bids/final offers from at least be three invited suppliers\n
                       -  Abstract of submitted Price Quotation\n
                       -  BAC Resolution recommending award of contract to Lowest Calculated Responsive Bid(LCRB)\n
                        In cases of two failed biddings:\n
                       -  Agency's offer for negotiations with selected suppliers, contractors or consultants\n
                       -  Certification of BAC on the failure of competetive bidding for the second time\n
                       -  Evidence of invitation of observers in all stages of the negotiation\n
                       -  Eligibility documents in case of infrastructure projects\n
                    >    In cases of emergency cases:\n
                       -  Justification as the necessity\n
                    >    In case of take-over contracts\n
                       -  Copy of terminated contract\n
                       -  Reasons for the termination\n
                       -  Negotiation documents with the second lowest calculated bidder or the third lowest calculated bidder in case of failure of negotiation with the second lowest bidder. If negotiation still fails, invitation to at least three eligible contractors\n
                       -  Approval by the Head of the Procuring Agency to negotiate contracts for projects under exceptional cases\n
                    >    In case of small value procurement\n
                       -  Letter/invitation to submit proposals\n
                    >    For adjacent or contiguous projects\n
                       -  Original contract and any document indicating that the same resulted from competetive bidding\n
                       -  Scope of work which should be related or similar to the scope of work of the original contract\n
                       -  Latest Accomplishment Report of the original contract showing that there was no negative slippage/delay
                 ",
                ],
            ],
            [
                'category' => 'Individual Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Teaching Personnel [Department of Education (DepEd), TESDA, SUCs and other educational institutions]',
                ],
            ],
            [
                'category' => 'Payroll Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Teaching Personnel [Department of Education (DepEd), TESDA, SUCs and other educational institutions]',
                ],
            ],
            [
                'category' => 'Individual Compensation for Salary/Wage (Permanent/Temporary/Casual)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Certified true copy of duly approved Appointment',
                    'Assignment Order, if applicable',
                    'Certified true copy of Oath of Office',
                    'Certificate of Assumption',
                    'Statement of Assets, Liabilities and Net Worth',
                    'Approved DTR',
                    'Bureu of Internal Revenue (BIR) withholding certificate (Forms 1902 and 2305)',
                    'Payroll Information on New Employee (PINE) (for agencies with computerized payroll systems)',
                    'Authority from the claimant and identification documents, if claimed by person other than the payee',
                    'Clearace from money, property and legal accountabilities from the previous office',
                    'Certified true copy of pre-audited disbursement voucher of last salary from previous agency and/or Certification by the Chief Accountant of last salary received from previous office duly verified by the assigned auditor thereat',
                    'BIR Form 2316 (Certificate of Compensation Payment/Tax Withheld)',
                    'Certificate of Available Leave Credits',
                    'Service Record',
                ],
            ],
            [
                'category' => 'Regular Payroll for Salaries/Wages (Permanent/Temporary/Casual)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Salary Payroll',
                    'Payroll Register (hard and soft copy)',
                    "Letter to the Bank to credit employees' account of their salaries or other claims",
                    'Validated deposit slips',
                ],
            ],
            [
                'category' => 'Individual Compensation for Part-Time Services',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Office Order',
                    "Coordinator's report on lecturer's schedule",
                    'Course Syllabus/Program of Lectures',
                    'Duly approved DTR in case of claims by the coordinator and facilitators',
                ],
            ],
            [
                'category' => 'Payroll Compensation for Part-Time Services',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Office Order',
                    "Coordinator's report on lecturer's schedule",
                    'Course Syllabus/Program of Lectures',
                    'Duly approved DTR in case of claims by the coordinator and facilitators',
                ],
            ],
            [
                'category' => 'Individual Salary/Wage (COS/JO/Laborer)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Invoices/receipts for GOCCs/GFIs and LGUs',
                    'Receipts and/or other documents evidencing disbursement, if there are available, or in lieu thereof, certification executed by the official concerned that the expense sought to be reimbursed have been incurred for any of the purposes contemplated under the provisions of the GAA in relation to or by reasons of his position, in case of NGAs',
                    'Other supporting documents as are necessary depending on the nature of expense charged',
                ],
            ],
            [
                'category' => 'Regular Payroll for Salaries/Wages (COS/JO/Laborer)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [
                    'Invoices/receipts for GOCCs/GFIs and LGUs',
                    'Receipts and/or other documents evidencing disbursement, if there are available, or in lieu thereof, certification executed by the official concerned that the expense sought to be reimbursed have been incurred for any of the purposes contemplated under the provisions of the GAA in relation to or by reasons of his position, in case of NGAs',
                    'Other supporting documents as are necessary depending on the nature of expense charged',
                ],
            ],
            [
                'category' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Individual Compensation for Special Allowances and Bonuses',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Payroll Compensation for Special Allowances and Bonuses',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Individual Compensation for Laborers, Student Assistants, Etc.',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Payroll Compensation for Laborers, Student Assistants, Etc.',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Remittance of Payroll Deductions',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],
            [
                'category' => 'Remittance of Taxes Withheld',
                'subcategory' => 'Default',
                'type' => 'Disbursements',
                'documents' => [],
            ],

        ];
    }
}
