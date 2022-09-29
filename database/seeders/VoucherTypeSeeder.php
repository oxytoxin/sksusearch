<?php

namespace Database\Seeders;

use App\Models\VoucherSubType;
use App\Models\VoucherType;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Cash Advances',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Local Travel',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Travel Order',
                'Request Letter',
                'Other supporting documents',
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Foreign Travel',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Travel Order',
                'Request Letter',
                'Other supporting documents',
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Activity, Program, Project, ETC.',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Special Disbursing Officer',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Reimbursements',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Local Travel',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Travel Order',
                'Request Letter',
                'Other supporting documents',
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Foreign Travel',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Travel Order',
                'Request Letter',
                'Other supporting documents',
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Activity, Program, Project, ETC.',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Supplies/Materials',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Purchase Orders',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Purchase Orders for Supplies, Materials, Equipment and Motor Vehicles',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Certificate of Exclusive Distributorship (if applicable)",
                "Samples and brochures/photographs (if applicable)",
                "(Imported Items) Consular Invoice/Pro-Forma invoice of the foreign supplier with the corresponding details",
                "(Imported Items) Home Consumption Value of the items",
                "(Imported Items) Breakdown of the expenses incurred in the importation",
                "Original copy of Dealers/Supplier's Invoices showing the quantity, description of the articles, unit and total value, duly Signed by the dealer or his representative, and indicating receipt by the proper agency official of items delivered",
                "Results of Test Analysis, if applicable",
                "Tax receipts from the Bureau of Customs or the BIR indicating the exact specifications and/or serial number of the equipment procured by the government as proof of payment of all taxes and duties due on the same equipment, supplied or sold to the govemment [Administrative Order (AO) No. 200 dated November 21, 1990]",
                "Inspection and Acceptance Report prepared by the",
                "Department/Agency property inspector and signed by the Head of Agency or his authorized representative",
                "For equipment, Property Acknowledgment Receipt",
                "Warranty Security for a minimum period of months, in the case of expendable supplies, or a minimum period of one year in the case of non-expendable supplies, after acceptance by the procuring entity of the delivered supplies",
                "Request for purchase of supplies, materials and equipmentduly approved by authorities",
                "In case of motor vehicles, (AO No. 233 dated August 1, 2008): Authority to purchase from Agency head and Secretary of DBM, or OP depending on the type of vehicle being provided (Sections 7 and 9)",
                "In case of motor vehicles, (AO No. 233 dated August 1, 2008): Authority to purchase from Local Chief Executives, including Punong Barangay, for types of vehicles enumerated under Section 7 of AO No. 233 sourced from their unencumbered local funds and if chargeable under the GAA, either from the DBM or OP depending on the of vehicles purchased (Sections 7 to 9)",
                "For procurement of drugs and medicicine: Certificate of product registration from Food and Drug Administration (FDA)",
                "For procurement of drugs and medicicine: Certificate of good manufacturing practice from FDA Batch Release Certificate from FDA",
                "For procurement of drugs and medicicine: If the supplier is not the manufacturer, certification from the manufacturer that the supplier is an authorized distributor/dealer of the products/items",
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'General Support Services (janitorial, security, maintenance, garbage collection and disposal and similar services)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "(Janitorial/security/maintenance services) appropriate approved documents indicating the number of personnel involved and their corresponding rates/salary",
                "(Janitorial/security/maintenance services) appropriate approved documents indicating the schedule of work and places of assignment or station/visits indicating, among others, the number of hours per visit",
                "(Janitorial/security/maintenance services) appropriate approved documents indicating the type and number of equipment to be served (in case of visitorial maintenance service)",
                "(Janitorial/security/maintenance services) The scaled floor plans of the building and other area/s covered by the service contract (for janitorial services)",
                "(Janitorial/security/maintenance services) The group classification of personnel to determine the Equivalent Equipment Monthly Statutory",
                "(Janitorial/security/maintenance services) Minimum Wage Rate in accordance with the applicable Rules Implementing RA No. 6727",
                "(Janitorial/security/maintenance services) Approved documents indicating the minimum requirements of the agency on the number of security personnel to be involved in the project (for security service contract)",
                "(Janitorial/security/maintenance services) The population of the agency where the services are rendered (for security service controls)",
                "(Janitorial/security/maintenance services) Detailed description of the maintenance services to be rendered or activities to be performed (for maintenance service contracts)",
                "(Garbage Collection and Disposal) Complete description/specifications (brand name, model, make/country of origin, hp, piston displacement, capacity) and number of units of dump frucks to be used",
                "(Garbage Collection and Disposal) Complete descriptions/specifications (age, condition, brand, etc.) and of units of all other equipment to be rented/used",
                "(Garbage Collection and Disposal) Appropriate approved documents containing the terms and conditions, whether operated or bare rental for heavy equipment, whether per trip or package deal; and other relevant condition",
                "(Garbage Collection and Disposal) The designated dumpsite/location of dumpsite (if provided in a separate document)",
                "(Garbage Collection and Disposal) The measurement in kilometers of the total distance covered by one complete route for all the required routes to be traveled",
                "(Garbage Collection and Disposal) Estimated volume in cubic meters of garbage to be hauled from area of operation, including the basis for such estimates",
                "(Garbage Collection and Disposal) In cases where the type of contract differs from the usual per u-ip contract basis, sufficient justification and comparative analysis between the type of contract adopted against the basic per trip type of contract",
                "(Forwarding/shipping/hauling contract) The and technical description of the mode of transportation used",
                "(Forwarding/shipping/hauling contract) The point Of origin and destination including the estimated distance/s if transported by land",
                "(Forwarding/shipping/hauling contract) The estimated weight and volume of cargoes involved",
                "Accomplishment Report",
                "Request for payment",
                "Contractor's Bill",
                "Certificate of Acceptance",
                "Record of Attendance/Service",
                "Proof of remittance to concemed government agency and/or GOCCs [BWSocial Security System (SSS)/Pag-1big]",
                "Such other documents peculiar to the contract and/or to the mode of procurement and considered necessary in the auditorial review and in the technical evaluation thereof",
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Rental Contracts',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "(For privately-owned office/building) Complete copy Of the building floor plans indicating in shaded colors the rentable space",
                "(For privately-owned office/building) Copy of the Certificate of (hcupancy of the building or appropriate approved documents showing the date the building was constructed or age of the building",
                "(For privately-owned office/building) Complete description of the building as to type, kind and class including its component parts and equipment facilities such as, but not limited to, parking areas, elevators, air-conditioning systems, firefighting equipment, etc.",
                "(For privately-owned office/building) Copy Of the Master Of Deed Declaration and Restrictions in case Of of office condominiums",
                "(For equipment rental/lease/purchase contract) Agency evaluation of equipment utilization",
                "(For equipment rental/lease/purchase contract) Pertinent data of area of operation",
                "List of prevailing comparable property within vicinity",
                "Vicinity map",
                "Request for payment",
                "Bill/lnvoices",
                "Certificate of occupancy (space/building)",
                "Such other documents peculiar to the contract and/or to the mode of procurement and considered necessary in the auditorial review and in the technical evaluation thereof",
            ],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Repair and Maintenance of Aircraft, Equipment and Motor Vehicles',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Copy of the pre-repair evaluation report and approved detailed plans by the agency showing in sufficient detail the scope of work/extent of repair to be done",
                "Copy of the latest service bulletin, in case of aircraft",
                "Report of waste materials",
                "Copy of document indicating the history of repair",
                "Post-inspection reports",
                "Warranty Certificate",
                "Request for payment",
                "Bill/lnvoices",
                "Certificate of Acceptance",
                "Pre-repair inspection reports",
                "Such other documents peculiar to the contract and/or to the mode of procurement and necessary in the auditorial review and in the technical evaluation thereof",
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Advertising Expenses',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Bill/Statement of Account",
                "Copy of newspaper clippings evidencing publication and/or CD in case of TV/Radio commercial",
            ],
        ]);


        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payment to Suppliers without Purchase Orders',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payment to Suppliers without Purchase Orders',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Activity Design or Memorandum",
                "Official receipt/invoice",
                "Canvass from at least 3 suppliers (if applicable)",
                "Inspection and Acceptance Report (IAR) if applicable",
                "Attendance sheet (if applicable)",
                "Documentation of event/activity",
                "Others",
            ],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Compensation',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for Salary/Wage (COS/JO)',
        ]);

        $vst->related_documents_list()->create([
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
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Certification from the Registrar/Dean of College that the load is in excess of the regular load or outside the regular office hours",
                "Schedule of classes indicating the designated teaching personnel",
                "Certificate of actual conduct of classes and/or Accomplishment Report",
                "Approved DTR/Service Report",
                "(Overtime Pay) Overtime authority stating the necessity and urgency of the work to be done, and the duration of overtime work",
                "(Overtime Pay) Overtime work program",
                "(Overtime Pay) Quantified Overtime accomplishment duly signed by the employee and supervisor",
                "(Overtime Pay) Certificate of service or duly approved DTR",
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for Salary/Wage (Permanent/Temporary/Casual)',
        ]);

        $vst->related_documents_list()->create([
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
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for Part-Time Services',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Office Order',
                "Coordinator's report on lecturer's schedule",
                'Course Syllabus/Program of Lectures',
                'Duly approved DTR in case of claims by the coordinator and facilitators',
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for Special Allowances and Bonuses',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Compensation for Laborers, Student Assistants, Etc.',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Compensation',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Compensation for Salaries/Wages (COS/JO)',
        ]);

        $vst->related_documents_list()->create([
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
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Compensation for PS (Overload/Overtime/Honorarium/Requested Subject/Others)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "Certification from the Registrar/Dean of College that the load is in excess of the regular load or outside the regular office hours",
                "Schedule of classes indicating the designated teaching personnel",
                "Certificate of actual conduct of classes and/or Accomplishment Report",
                "Approved DTR/Service Report",
                "(Overtime Pay) Overtime authority stating the necessity and urgency of the work to be done, and the duration of overtime work",
                "(Overtime Pay) Overtime work program",
                "(Overtime Pay) Quantified Overtime accomplishment duly signed by the employee and supervisor",
                "(Overtime Pay) Certificate of service or duly approved DTR",
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Compensation for Part-Time Services',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Office Order',
                "Coordinator's report on lecturer's schedule",
                'Course Syllabus/Program of Lectures',
                'Duly approved DTR in case of claims by the coordinator and facilitators',
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Compensation for Special Allowances and Bonuses',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Compensation for Laborers, Student Assistants, Etc.',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Utilities, Fuel, Internet, Telephone, Etc.',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Utilities, Fuel, Internet, Telephone, Etc.',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Statement of Account/Bill (for pre-audit purposes)',
                'Invoice/Official Receipt or machine validated statement of account/bill (for post-audit purposes)',
                '(For Telephone/Communication Services) Certification by Agency Head or his authorized representatives that all National Direct Dial (NDD), National Operator Assisted Calls and International Operator Assisted Calls are official calls',
            ],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payment to Contractors of Infrastructure Projects',
        ]);


        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payment to Contractors of Infrastructure Projects',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                "(Infrastructure) Letter request from contractors for advance/progress/final payment or for substitution in case of release of retention money",
                "(Infrastructure) Statement of Work Accomplished/Progress Billing",
                "(Infrastructure) Inspection Report by the Agency's Authorized Engineer",
                "(Infrastructure) Results of Test Analysis, if applicable",
                "(Infrastructure) Statement of Time Elapsed",
                "(Infrastructure) Monthly Certificate of Payment",
                "(Infrastructure) Contractor's Affidavit on payment of laborers and materials",
                "(Infrastructure) Pictures, before, during and after construction of items of work especially the embedded items",
                "(Infrastructure) Photocopy of vouchers of all previous payments",
                "(Infrastructure) Certificate of completion",
                "(Infrastructure: Advance Payment) Irrevocable Standby Letter of Credit/Security Bond/Bank Guarantee",
                "(Infrastructure: Advance Payment) Such other documents peculiar to the contact and/or to the mode of procurement and considered necessary in the auditorial review and in the technical evaluation thereof",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of Approved Change Order (CO)/Extra Work Order (EWO)",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of the approved original plans indicating the affected portion(s) Of the project and duly revised plans and specifications, if applicable, indicating the changes made which shall be color coded",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of the agency's report establishing the necessity/justification(s) for the need of such CO and/or EWO which shall include: (a) the computation as to the quantities of the additional works involved per item indicating the stations where such works are needed; (b) the date of conducted and the results of such inspection; (c) a detailed estimate of the unit cost of such items of work for new unit costs including those expressed in volume/area/lump-sum/lot",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of the approved/revised PERT/CPM Network Diagram which shall be color coded, reflecting the effect of additional/deductive time on the contract period and the corresponding detailed computations for the additional/deductive time for the subject Change Work Order",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of the approved detailed breakdown of contract cost for the variation order",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Copy of the COA Technical Evaluation Report for the original contract",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) If the Variation Order to be reviewed is not the 1st variation order, all of the above requirements for all previously approved variation orders, if not yet reviewed, otherwise, copy of the COA Technical",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Evaluation Report for the previously approved variation orders",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Additional performance security in the prescribed form and amount if variation order exceeds 10 percent of the original contract cost",
                "(Infrastructure: Variation Order/Change Order/Extra Work Order) Such other documents peculiar to the contract and/or to the mode of procurement and considered necessary in the auditorial review and in the technical evaluation thereof",
                "(Infrastructure: Final Payment) As-Built plans",
                "(Infrastructure: Final Payment) Warranty security",
                "(Infrastructure: Final Payment) Clearance from the Provincial Treasurer that the corresponding sand and gravel fees have been paid [DPWH Department Order No. 109 s. 1993 dated May 4, 1993 and DO No. 119 s. 1993 dated May 11, 19931",
                "(Infrastructure: Final Payment) Copy of tum over documents/transfer of project and facilities such as motor vehicle, laptops, other equipment and fumiture included in the contract to concemed government agency",
                "(Infrastructure: Release of Retention Money) Any security in the form of cash, bank guarantee, irrevocable standby letter of credit from a commercial bank, GSIS or surety bond callable on demand",
                "(Infrastructure: Release of Retention Money) Certification from the end-user that the project is completed and inspected",
                "(Direct Contracting) Copy of letter to selected manufacturer/supplier/distributor to submit a price quotation and conditions of sale",
                "(Direct Contracting) Certificate of Exclusive Distributorship issued by the principal under oath and authenticated by the embassy/consulate nearest the place of the principal, in case of foreign supplier",
                "(Direct Contracting) Certification from the agency authorized official that there are no sub-dealers selling at lower prices and for which no suitable substitute can be obtained at more advantageous terms to the government",
                "(Direct Contracting) Certification of the BAC in case of procurement of critical plant components and/or to maintain certain standards",
                "(Direct Contracting) Study/survey done to determine that there are no sub-dealers selling at lower prices and for which no suitable substitute can be obtained at more advantageous terms of government",
                "(Direct Contracting) Such other documents peculiar to the contract and/or to the mode of procurement and considered necessary in the auditorial review and in technical evaluation thereof",
                "(Negotiated Procurement) Price quotation/bids/final offers from at least be three invited suppliers",
                "(Negotiated Procurement) Abstract of submitted Price Quotation",
                "(Negotiated Procurement) BAC Resolution recommending award of contract to Lowest Calculated Responsive Bid(LCRB)",
                "(Negotiated Procurement: two failed biddings) Agency's offer for negotiations with selected suppliers, contractors or consultants",
                "(Negotiated Procurement: two failed biddings) Certification of BAC on the failure of competetive bidding for the second time",
                "(Negotiated Procurement: two failed biddings) Evidence of invitation of observers in all stages of the negotiation",
                "(Negotiated Procurement: two failed biddings) Eligibility documents in case of infrastructure projects",
                "(Negotiated Procurement: emergency cases) Justification as the necessity",
                "(Negotiated Procurement: take-over contracts) Copy of terminated contract",
                "(Negotiated Procurement: take-over contracts) Reasons for the termination",
                "(Negotiated Procurement: take-over contracts) Negotiation documents with the second lowest calculated bidder or the third lowest calculated bidder in case of failure of negotiation with the second lowest bidder. If negotiation still fails, invitation to at least three eligible contractors",
                "(Negotiated Procurement: take-over contracts) Approval by the Head of the Procuring Agency to negotiate contracts for projects under exceptional cases",
                "(Negotiated Procurement: small value procurement) Letter/invitation to submit proposals",
                "(Negotiated Procurement: adjacent or contiguous projects) Original contract and any document indicating that the same resulted from competetive bidding",
                "(Negotiated Procurement: adjacent or contiguous projects) Scope of work which should be related or similar to the scope of work of the original contract",
                "(Negotiated Procurement: adjacent or contiguous projects) Latest Accomplishment Report of the original contract showing that there was no negative slippage/delay",
            ],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Regular Payroll',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Regular Payroll for Salaries/Wages (Permanent/Temporary/Casual)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Salary Payroll',
                'Payroll Register (hard and soft copy)',
                "Letter to the Bank to credit employees' account of their salaries or other claims",
                'Validated deposit slips',
            ],
        ]);
        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Regular Payroll for Salaries/Wages (COS/JO/Laborer)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Invoices/receipts for GOCCs/GFIs and LGUs',
                'Receipts and/or other documents evidencing disbursement, if there are available, or in lieu thereof, certification executed by the official concerned that the expense sought to be reimbursed have been incurred for any of the purposes contemplated under the provisions of the GAA in relation to or by reasons of his position, in case of NGAs',
                'Other supporting documents as are necessary depending on the nature of expense charged',
            ],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Salary/Wage (COS/JO/Laborer)',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Salary/Wage (COS/JO/Laborer)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [
                'Invoices/receipts for GOCCs/GFIs and LGUs',
                'Receipts and/or other documents evidencing disbursement, if there are available, or in lieu thereof, certification executed by the official concerned that the expense sought to be reimbursed have been incurred for any of the purposes contemplated under the provisions of the GAA in relation to or by reasons of his position, in case of NGAs',
                'Other supporting documents as are necessary depending on the nature of expense charged',
            ],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Individual Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Payroll Pay/Honorarium for External Experts/Professionals (Activity/Project/Program-Based)',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vt = VoucherType::create([
            'voucher_category_id' => 1,
            'name' => 'Remittance',
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Remittance of Payroll Deductions',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);

        $vst = $vt->voucher_subtypes()->create([
            'name' => 'Remittance of Taxes Withheld',
        ]);

        $vst->related_documents_list()->create([
            'documents' => [],
        ]);
    }
}
