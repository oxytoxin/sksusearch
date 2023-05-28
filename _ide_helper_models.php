<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\ActivityLog
 *
 * @property int $id
 * @property string $loggable_type
 * @property int $loggable_id
 * @property string $description
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $loggable
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereLoggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereLoggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperActivityLog {}
}

namespace App\Models{
/**
 * App\Models\ArchiveDocument
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchiveDocument whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperArchiveDocument {}
}

namespace App\Models{
/**
 * App\Models\ArchivedCheque
 *
 * @property int $id
 * @property string $cheque_number
 * @property string|null $payee
 * @property int $cheque_amount
 * @property \Carbon\CarbonImmutable|null $cheque_date
 * @property int|null $cheque_state
 * @property int|null $building_id
 * @property int|null $shelf_id
 * @property int|null $drawer_id
 * @property int|null $folder_id
 * @property int|null $fund_cluster_id
 * @property array|null $other_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ScannedDocument> $scanned_documents
 * @property-read int|null $scanned_documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereChequeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereChequeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereChequeState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereDrawerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereFundClusterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereShelfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArchivedCheque whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperArchivedCheque {}
}

namespace App\Models{
/**
 * App\Models\Bond
 *
 * @property int $id
 * @property int $amount
 * @property string|null $bond_certificate_number
 * @property string $validity_date_from
 * @property string|null $validity_date_to
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EmployeeInformation|null $employee_information
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bond newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereBondCertificateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereValidityDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereValidityDateTo($value)
 * @mixin \Eloquent
 */
	class IdeHelperBond {}
}

namespace App\Models{
/**
 * App\Models\Campus
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $campus_code
 * @property string|null $telephone
 * @property string|null $email
 * @property int|null $admin_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ElectricityMeter> $electricity_meters
 * @property-read int|null $electricity_meters_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeInformation> $employee_information
 * @property-read int|null $employee_information_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InternetAccountNumber> $internet_accounts_numbers
 * @property-read int|null $internet_accounts_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Office> $offices
 * @property-read int|null $offices_count
 * @property-read \App\Models\PettyCashFund|null $petty_cash_fund
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TelephoneAccountNumber> $telephone_account_numbers
 * @property-read int|null $telephone_account_numbers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle> $vehicle
 * @property-read int|null $vehicle_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WaterMeter> $water_meters
 * @property-read int|null $water_meters_count
 * @method static \Illuminate\Database\Eloquent\Builder|Campus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereAdminUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereCampusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCampus {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucher
 *
 * @property int $id
 * @property int $voucher_subtype_id
 * @property int $user_id
 * @property int $signatory_id
 * @property bool $certified_by_accountant
 * @property int|null $mop_id
 * @property int|null $travel_order_id
 * @property string $tracking_number
 * @property string $payee
 * @property string|null $cheque_number
 * @property string|null $ors_burs
 * @property string|null $dv_number
 * @property \Carbon\CarbonImmutable|null $due_date
 * @property \Carbon\CarbonImmutable|null $closed_at
 * @property \Carbon\CarbonImmutable|null $journal_date
 * @property \Carbon\CarbonImmutable|null $submitted_at
 * @property \Carbon\CarbonImmutable|null $documents_verified_at
 * @property string|null $log_number
 * @property array|null $other_details
 * @property bool $for_cancellation
 * @property \Carbon\CarbonImmutable|null $cancelled_at
 * @property array|null $draft
 * @property array|null $related_documents
 * @property int|null $fund_cluster_id
 * @property int|null $current_step_id
 * @property int|null $previous_step_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activity_logs
 * @property-read int|null $activity_logs_count
 * @property-read \App\Models\DisbursementVoucherStep|null $current_step
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucherParticular> $disbursement_voucher_particulars
 * @property-read int|null $disbursement_voucher_particulars_count
 * @property-read \App\Models\FundCluster|null $fund_cluster
 * @property-read \App\Models\LiquidationReport|null $liquidation_report
 * @property-read \App\Models\Mop|null $mop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCashFundRecord> $petty_cash_fund_records
 * @property-read int|null $petty_cash_fund_records_count
 * @property-read \App\Models\DisbursementVoucherStep|null $previous_step
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ScannedDocument> $scanned_documents
 * @property-read int|null $scanned_documents_count
 * @property-read \App\Models\User|null $signatory
 * @property-read \App\Models\TravelCompletedCertificate|null $travel_completed_certificate
 * @property-read \App\Models\TravelOrder|null $travel_order
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\VoucherSubType|null $voucher_subtype
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCertifiedByAccountant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCurrentStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDocumentsVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDvNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereForCancellation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereFundClusterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereLogNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereMopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereOrsBurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher wherePreviousStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereRelatedDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereSignatoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereVoucherSubtypeId($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisbursementVoucher {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucherParticular
 *
 * @property int $id
 * @property int $disbursement_voucher_id
 * @property string $purpose
 * @property int $amount
 * @property string|null $responsibility_center
 * @property string|null $mfo_pap
 * @property int|null $suggested_amount
 * @property int|null $final_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereFinalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereMfoPap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereResponsibilityCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereSuggestedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisbursementVoucherParticular {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucherStep
 *
 * @property int $id
 * @property int|null $office_group_id
 * @property bool $enabled
 * @property string $process
 * @property string $recipient
 * @property string|null $sender
 * @property int $return_step_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $current_disbursement_vouchers
 * @property-read int|null $current_disbursement_vouchers_count
 * @property-read \App\Models\OfficeGroup|null $office_group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $previous_disbursement_vouchers
 * @property-read int|null $previous_disbursement_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereOfficeGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereReturnStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDisbursementVoucherStep {}
}

namespace App\Models{
/**
 * App\Models\Dte
 *
 * @property int $id
 * @property int $amount
 * @property int $philippine_region_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PhilippineRegion|null $philippine_region
 * @method static \Illuminate\Database\Eloquent\Builder|Dte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dte newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dte query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dte whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dte wherePhilippineRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dte whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperDte {}
}

namespace App\Models{
/**
 * App\Models\ElectricityMeter
 *
 * @property int $id
 * @property int|null $campus_id
 * @property string $meter_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus|null $campus
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter query()
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter whereMeterNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ElectricityMeter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperElectricityMeter {}
}

namespace App\Models{
/**
 * App\Models\EmployeeInformation
 *
 * @property int $id
 * @property int $active
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $full_name
 * @property string|null $address
 * @property \Carbon\CarbonImmutable|null $birthday
 * @property string|null $contact_number
 * @property int $user_id
 * @property int|null $position_id
 * @property int|null $office_id
 * @property int|null $campus_id
 * @property int|null $bond_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bond|null $bond
 * @property-read \App\Models\Campus|null $campus
 * @property-read \App\Models\Office|null $office
 * @property-read \App\Models\Position|null $position
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereBondId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperEmployeeInformation {}
}

namespace App\Models{
/**
 * App\Models\FundCluster
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster query()
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FundCluster whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperFundCluster {}
}

namespace App\Models{
/**
 * App\Models\InternetAccountNumber
 *
 * @property int $id
 * @property int $campus_id
 * @property string $account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InternetAccountNumber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperInternetAccountNumber {}
}

namespace App\Models{
/**
 * App\Models\Itinerary
 *
 * @property int $id
 * @property int $is_actual
 * @property int $travel_order_id
 * @property int $user_id
 * @property string|null $purpose
 * @property array $coverage
 * @property \Carbon\CarbonImmutable|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryEntry> $itinerary_entries
 * @property-read int|null $itinerary_entries_count
 * @property-read \App\Models\TravelOrder|null $travel_order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary actualItinerary()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereCoverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereIsActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperItinerary {}
}

namespace App\Models{
/**
 * App\Models\ItineraryEntry
 *
 * @property int $id
 * @property int $itinerary_id
 * @property int $mot_id
 * @property \Carbon\CarbonImmutable $date
 * @property string $place
 * @property \Carbon\CarbonImmutable $departure_time
 * @property \Carbon\CarbonImmutable $arrival_time
 * @property int $transportation_expenses
 * @property int $other_expenses
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Itinerary|null $itinerary
 * @property-read \App\Models\Mot|null $mot
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereMotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereOtherExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry wherePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereTransportationExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItineraryEntry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperItineraryEntry {}
}

namespace App\Models{
/**
 * App\Models\LegacyDocument
 *
 * @property int $id
 * @property string $dv_number
 * @property string $document_code
 * @property string $payee_name
 * @property array $particulars
 * @property array|null $other_details
 * @property \Carbon\CarbonImmutable $journal_date
 * @property \Carbon\CarbonImmutable $upload_date
 * @property int|null $building_id
 * @property int|null $shelf_id
 * @property int|null $drawer_id
 * @property int|null $folder_id
 * @property int|null $fund_cluster_id
 * @property int|null $cheque_state
 * @property \Carbon\CarbonImmutable|null $cheque_date
 * @property int|null $cheque_amount
 * @property string|null $cheque_number
 * @property int $document_category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FundCluster|null $fund_cluster
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ScannedDocument> $scanned_documents
 * @property-read int|null $scanned_documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereChequeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereChequeDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereChequeState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereDocumentCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereDocumentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereDrawerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereDvNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereFolderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereFundClusterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument wherePayeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereShelfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegacyDocument whereUploadDate($value)
 * @mixin \Eloquent
 */
	class IdeHelperLegacyDocument {}
}

namespace App\Models{
/**
 * App\Models\LiquidationReport
 *
 * @property int $id
 * @property string $tracking_number
 * @property int $disbursement_voucher_id
 * @property array $particulars
 * @property int $user_id
 * @property int $signatory_id
 * @property bool $certified_by_accountant
 * @property int $reimbursement_waived
 * @property array|null $refund_particulars
 * @property string|null $lr_number
 * @property \Carbon\CarbonImmutable|null $report_date
 * @property \Carbon\CarbonImmutable|null $signatory_date
 * @property \Carbon\CarbonImmutable|null $journal_date
 * @property int $for_cancellation
 * @property \Carbon\CarbonImmutable|null $cancelled_at
 * @property int $current_step_id
 * @property int $previous_step_id
 * @property array|null $draft
 * @property array $related_documents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ActivityLog> $activity_logs
 * @property-read int|null $activity_logs_count
 * @property-read \App\Models\LiquidationReportStep|null $current_step
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @property-read \App\Models\LiquidationReportStep|null $previous_step
 * @property-read \App\Models\User $requisitioner
 * @property-read \App\Models\User|null $signatory
 * @property-read \App\Models\TravelCompletedCertificate|null $travel_completed_certificate
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereCertifiedByAccountant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereCurrentStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereForCancellation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereLrNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport wherePreviousStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereRefundParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereReimbursementWaived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereRelatedDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereSignatoryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereSignatoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReport whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperLiquidationReport {}
}

namespace App\Models{
/**
 * App\Models\LiquidationReportStep
 *
 * @property int $id
 * @property int|null $office_group_id
 * @property bool $enabled
 * @property string $process
 * @property string|null $recipient
 * @property string|null $sender
 * @property int $return_step_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LiquidationReport> $current_liquidation_reports
 * @property-read int|null $current_liquidation_reports_count
 * @property-read \App\Models\OfficeGroup|null $office_group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LiquidationReport> $previous_liquidation_reports
 * @property-read int|null $previous_liquidation_reports_count
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereOfficeGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereReturnStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LiquidationReportStep whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperLiquidationReportStep {}
}

namespace App\Models{
/**
 * App\Models\Mop
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $disbursement_vouchers
 * @property-read int|null $disbursement_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMop {}
}

namespace App\Models{
/**
 * App\Models\Mot
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItineraryEntry> $itinerary_entries
 * @property-read int|null $itinerary_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperMot {}
}

namespace App\Models{
/**
 * App\Models\Office
 *
 * @property int $id
 * @property int|null $office_group_id
 * @property string $name
 * @property string $code
 * @property int $campus_id
 * @property int|null $head_position_id
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeInformation> $employee_information
 * @property-read int|null $employee_information_count
 * @property-read \App\Models\EmployeeInformation|null $head_employee
 * @property-read \App\Models\Position|null $head_position
 * @property-read \App\Models\OfficeGroup|null $office_group
 * @method static \Illuminate\Database\Eloquent\Builder|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereHeadPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereOfficeGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOffice {}
}

namespace App\Models{
/**
 * App\Models\OfficeGroup
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DisbursementVoucherStep|null $disbursement_voucher_final_step
 * @property-read \App\Models\DisbursementVoucherStep|null $disbursement_voucher_starting_step
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucherStep> $disbursement_voucher_steps
 * @property-read int|null $disbursement_voucher_steps_count
 * @property-read \App\Models\LiquidationReportStep|null $liquidation_report_final_step
 * @property-read \App\Models\LiquidationReportStep|null $liquidation_report_starting_step
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LiquidationReportStep> $liquidation_report_steps
 * @property-read int|null $liquidation_report_steps_count
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperOfficeGroup {}
}

namespace App\Models{
/**
 * App\Models\OicUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $oic_id
 * @property string $valid_from
 * @property string|null $valid_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $oic
 * @property-read \App\Models\User $signatory
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereOicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OicUser whereValidTo($value)
 * @mixin \Eloquent
 */
	class IdeHelperOicUser {}
}

namespace App\Models{
/**
 * App\Models\PettyCashFund
 *
 * @property int $id
 * @property int $custodian_id
 * @property int $campus_id
 * @property int $voucher_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @property-read \App\Models\User|null $custodian
 * @property-read \App\Models\PettyCashFundRecord|null $latest_petty_cash_fund_record
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCashFundRecord> $petty_cash_fund_records
 * @property-read int|null $petty_cash_fund_records_count
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund query()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereCustodianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFund whereVoucherLimit($value)
 * @mixin \Eloquent
 */
	class IdeHelperPettyCashFund {}
}

namespace App\Models{
/**
 * App\Models\PettyCashFundRecord
 *
 * @property int $id
 * @property string $recordable_type
 * @property int $recordable_id
 * @property int $type
 * @property string $nature_of_payment
 * @property int $amount
 * @property int $running_balance
 * @property int $petty_cash_fund_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PettyCashFund|null $petty_cash_fund
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $recordable
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereNatureOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord wherePettyCashFundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereRecordableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereRecordableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereRunningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashFundRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPettyCashFundRecord {}
}

namespace App\Models{
/**
 * App\Models\PettyCashVoucher
 *
 * @property int $id
 * @property string $tracking_number
 * @property string|null $entity_name
 * @property int $fund_cluster_id
 * @property int $petty_cash_fund_id
 * @property string|null $pcv_number
 * @property \Carbon\CarbonImmutable $pcv_date
 * @property string|null $payee
 * @property string|null $address
 * @property int $custodian_id
 * @property int $requisitioner_id
 * @property int $signatory_id
 * @property string|null $responsibility_center
 * @property array|null $particulars
 * @property int $amount_granted
 * @property int $amount_paid
 * @property bool $is_liquidated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $custodian
 * @property-read \App\Models\FundCluster|null $fund_cluster
 * @property-read \App\Models\PettyCashFund|null $petty_cash_fund
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCashFundRecord> $petty_cash_fund_records
 * @property-read int|null $petty_cash_fund_records_count
 * @property-read \App\Models\User|null $requisitioner
 * @property-read \App\Models\User|null $signatory
 * @method static \Database\Factories\PettyCashVoucherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereAmountGranted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereCustodianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereEntityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereFundClusterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereIsLiquidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereParticulars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher wherePcvDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher wherePcvNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher wherePettyCashFundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereRequisitionerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereResponsibilityCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereSignatoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashVoucher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPettyCashVoucher {}
}

namespace App\Models{
/**
 * App\Models\PhilippineCity
 *
 * @property int $id
 * @property string $psgc_code
 * @property string $city_municipality_description
 * @property string $region_description
 * @property string $province_code
 * @property string $city_municipality_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\PhilippineProvince|null $province
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereCityMunicipalityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereCityMunicipalityDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity wherePsgcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereRegionDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineCity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPhilippineCity {}
}

namespace App\Models{
/**
 * App\Models\PhilippineProvince
 *
 * @property int $id
 * @property string $psgc_code
 * @property string $province_description
 * @property string $region_code
 * @property string $province_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\PhilippineRegion|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereProvinceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince wherePsgcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineProvince whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPhilippineProvince {}
}

namespace App\Models{
/**
 * App\Models\PhilippineRegion
 *
 * @property int $id
 * @property string $psgc_code
 * @property string $region_description
 * @property string $region_code
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Dte|null $dte
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion wherePsgcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion whereRegionDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhilippineRegion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPhilippineRegion {}
}

namespace App\Models{
/**
 * App\Models\Position
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EmployeeInformation> $employee_information
 * @property-read int|null $employee_information_count
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperPosition {}
}

namespace App\Models{
/**
 * App\Models\RelatedDocumentsList
 *
 * @property int $id
 * @property int $voucher_sub_type_id
 * @property array $documents
 * @property array $liquidation_report_documents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VoucherSubType|null $voucher_sub_type
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList query()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereLiquidationReportDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereVoucherSubTypeId($value)
 * @mixin \Eloquent
 */
	class IdeHelperRelatedDocumentsList {}
}

namespace App\Models{
/**
 * App\Models\RequestSchedule
 *
 * @property int $id
 * @property int $request_type
 * @property int|null $travel_order_id
 * @property string|null $driver_id
 * @property int|null $requested_by_id
 * @property string|null $vehicle_id
 * @property string $purpose
 * @property int|null $philippine_region_id
 * @property int|null $philippine_province_id
 * @property int|null $philippine_city_id
 * @property string|null $other_details
 * @property string $date_of_travel_from
 * @property string|null $date_of_travel_to
 * @property mixed|null $travel_dates
 * @property mixed|null $available_travel_dates
 * @property string|null $time_start
 * @property string|null $time_end
 * @property string|null $status
 * @property string|null $remarks
 * @property string|null $approved_at
 * @property string|null $rejected_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $applicants
 * @property-read int|null $applicants_count
 * @property-read \App\Models\EmployeeInformation|null $driver
 * @property-read \App\Models\PhilippineCity|null $philippine_city
 * @property-read \App\Models\PhilippineProvince|null $philippine_province
 * @property-read \App\Models\PhilippineRegion|null $philippine_region
 * @property-read \App\Models\User|null $requested_by
 * @property-read \App\Models\Vehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereAvailableTravelDates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereDateOfTravelFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereDateOfTravelTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule wherePhilippineCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule wherePhilippineProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule wherePhilippineRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereRequestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereRequestedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereTravelDates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestSchedule whereVehicleId($value)
 * @mixin \Eloquent
 */
	class IdeHelperRequestSchedule {}
}

namespace App\Models{
/**
 * App\Models\ScannedDocument
 *
 * @property int $id
 * @property string $documentable_type
 * @property int $documentable_id
 * @property string $path
 * @property string $document_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $documentable
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereDocumentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereDocumentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereDocumentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScannedDocument whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperScannedDocument {}
}

namespace App\Models{
/**
 * App\Models\Sidenote
 *
 * @property int $id
 * @property string $sidenoteable_type
 * @property int $sidenoteable_id
 * @property string $content
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $sidenoteable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereSidenoteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereSidenoteableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sidenote whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperSidenote {}
}

namespace App\Models{
/**
 * App\Models\TelephoneAccountNumber
 *
 * @property int $id
 * @property int $campus_id
 * @property string $account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelephoneAccountNumber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTelephoneAccountNumber {}
}

namespace App\Models{
/**
 * App\Models\TravelCompletedCertificate
 *
 * @property int $id
 * @property int $user_id
 * @property int $signatory_id
 * @property int $travel_order_id
 * @property int|null $itinerary_id
 * @property int|null $liquidation_report_id
 * @property int|null $disbursement_voucher_id
 * @property int $condition
 * @property string|null $explanation
 * @property array|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @property-read \App\Models\LiquidationReport|null $liquidation_report
 * @property-read \App\Models\User|null $signatory
 * @property-read \App\Models\TravelOrder|null $travel_order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereItineraryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereLiquidationReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereSignatoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelCompletedCertificate whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperTravelCompletedCertificate {}
}

namespace App\Models{
/**
 * App\Models\TravelOrder
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $travel_order_type_id
 * @property \Illuminate\Support\Carbon $date_from
 * @property \Illuminate\Support\Carbon $date_to
 * @property string $purpose
 * @property bool $has_registration
 * @property int $registration_amount
 * @property int|null $philippine_region_id
 * @property int|null $philippine_province_id
 * @property int|null $philippine_city_id
 * @property string|null $other_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $needs_vehicle
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $applicants
 * @property-read int|null $applicants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $disbursement_vouchers
 * @property-read int|null $disbursement_vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \App\Models\PhilippineCity|null $philippine_city
 * @property-read \App\Models\PhilippineProvince|null $philippine_province
 * @property-read \App\Models\PhilippineRegion|null $philippine_region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sidenote> $sidenotes
 * @property-read int|null $sidenotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $signatories
 * @property-read int|null $signatories_count
 * @property-read \App\Models\TravelOrderType|null $travel_order_type
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder approved()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereHasRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereNeedsVehicle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereRegistrationAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereTravelOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTravelOrder {}
}

namespace App\Models{
/**
 * App\Models\TravelOrderType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrderType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperTravelOrderType {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bond|null $bond
 * @property-read \App\Models\Campus|null $campus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $disbursement_vouchers
 * @property-read int|null $disbursement_vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DisbursementVoucher> $disbursement_vouchers_to_sign
 * @property-read int|null $disbursement_vouchers_to_sign_count
 * @property-read \App\Models\EmployeeInformation|null $employee_information
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Itinerary> $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Office|null $office_administered
 * @property-read \App\Models\Office|null $office_headed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $officers_in_charge
 * @property-read int|null $officers_in_charge_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Office> $offices_in_charge
 * @property-read int|null $offices_in_charge_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $oic_for_users
 * @property-read int|null $oic_for_users_count
 * @property-read \App\Models\PettyCashFund|null $petty_cash_fund
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestSchedule> $request_applicants
 * @property-read int|null $request_applicants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sidenote> $sidenotes
 * @property-read int|null $sidenotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TravelOrder> $travel_order_applications
 * @property-read int|null $travel_order_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TravelOrder> $travel_order_signatories
 * @property-read int|null $travel_order_signatories_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Vehicle
 *
 * @property int $id
 * @property string $model
 * @property string $plate_number
 * @property string $campus_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus $campus
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle wherePlateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperVehicle {}
}

namespace App\Models{
/**
 * App\Models\VoucherCategory
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VoucherType> $voucher_types
 * @property-read int|null $voucher_types_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperVoucherCategory {}
}

namespace App\Models{
/**
 * App\Models\VoucherSubType
 *
 * @property int $id
 * @property string $name
 * @property int $voucher_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RelatedDocumentsList|null $related_documents_list
 * @property-read \App\Models\VoucherType|null $voucher_type
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereVoucherTypeId($value)
 * @mixin \Eloquent
 */
	class IdeHelperVoucherSubType {}
}

namespace App\Models{
/**
 * App\Models\VoucherType
 *
 * @property int $id
 * @property int $voucher_category_id
 * @property string $name
 * @property int $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VoucherCategory|null $voucher_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VoucherSubType> $voucher_subtypes
 * @property-read int|null $voucher_subtypes_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereVoucherCategoryId($value)
 * @mixin \Eloquent
 */
	class IdeHelperVoucherType {}
}

namespace App\Models{
/**
 * App\Models\WaterMeter
 *
 * @property int $id
 * @property int|null $campus_id
 * @property string $meter_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Campus|null $campus
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter query()
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter whereMeterNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WaterMeter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperWaterMeter {}
}

