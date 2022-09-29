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
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Bond
 *
 * @property int $id
 * @property int $amount
 * @property \Carbon\CarbonImmutable $validity_date
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EmployeeInformation|null $employee_information
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Bond newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bond whereValidityDate($value)
 */
	class Bond extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $offices
 * @property-read int|null $offices_count
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
 */
	class Campus extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucher
 *
 * @property int $id
 * @property int $voucher_subtype_id
 * @property int $user_id
 * @property int $signatory_id
 * @property int $certified_by_accountant
 * @property int $mop_id
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
 * @property array|null $draft
 * @property int|null $fund_cluster_id
 * @property int|null $current_step_id
 * @property int|null $previous_step_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ActivityLog[] $activity_logs
 * @property-read int|null $activity_logs_count
 * @property-read \App\Models\DisbursementVoucherStep|null $current_step
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucherParticular[] $disbursement_voucher_particulars
 * @property-read int|null $disbursement_voucher_particulars_count
 * @property-read \App\Models\Mop|null $mop
 * @property-read \App\Models\DisbursementVoucherStep|null $previous_step
 * @property-read \App\Models\User|null $signatory
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\VoucherSubType|null $voucher_subtype
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCertifiedByAccountant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereCurrentStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereDvNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereFundClusterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereJournalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereMopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereOrsBurs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher wherePreviousStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereSignatoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucher whereVoucherSubtypeId($value)
 */
	class DisbursementVoucher extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucherParticular
 *
 * @property int $id
 * @property int $disbursement_voucher_id
 * @property string $purpose
 * @property int $amount
 * @property string $responsibility_center
 * @property string $mfo_pap
 * @property int $suggested_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DisbursementVoucher|null $disbursement_voucher
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereDisbursementVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereMfoPap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereResponsibilityCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereSuggestedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherParticular whereUpdatedAt($value)
 */
	class DisbursementVoucherParticular extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DisbursementVoucherStep
 *
 * @property int $id
 * @property string $process
 * @property string $recipient
 * @property string|null $sender
 * @property int|null $office_id
 * @property int $return_step_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucher[] $current_disbursement_vouchers
 * @property-read int|null $current_disbursement_vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucher[] $previous_disbursement_vouchers
 * @property-read int|null $previous_disbursement_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereReturnStepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisbursementVoucherStep whereUpdatedAt($value)
 */
	class DisbursementVoucherStep extends \Eloquent {}
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
 */
	class Dte extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmployeeInformation
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $full_name
 * @property string|null $address
 * @property \Carbon\CarbonImmutable|null $birthday
 * @property string|null $contact_number
 * @property int $user_id
 * @property int $role_id
 * @property int|null $position_id
 * @property int|null $office_id
 * @property int|null $bond_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bond|null $bond
 * @property-read \App\Models\Office|null $office
 * @property-read \App\Models\Position|null $position
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereBondId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeInformation whereUserId($value)
 */
	class EmployeeInformation extends \Eloquent {}
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
 */
	class FundCluster extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Itinerary
 *
 * @property int $id
 * @property int $travel_order_id
 * @property int $user_id
 * @property array $coverage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ItineraryEntry[] $itinerary_entries
 * @property-read int|null $itinerary_entries_count
 * @property-read \App\Models\TravelOrder|null $travel_order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary query()
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereCoverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereTravelOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Itinerary whereUserId($value)
 */
	class Itinerary extends \Eloquent {}
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
 */
	class ItineraryEntry extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Mop
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucher[] $disbursement_vouchers
 * @property-read int|null $disbursement_vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mop whereUpdatedAt($value)
 */
	class Mop extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Mot
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ItineraryEntry[] $itinerary_entries
 * @property-read int|null $itinerary_entries_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mot whereUpdatedAt($value)
 */
	class Mot extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Office
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $campus_id
 * @property int|null $head_id
 * @property int|null $admin_user_id
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $admin
 * @property-read \App\Models\Campus|null $campus
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeInformation[] $employee_informations
 * @property-read int|null $employee_informations_count
 * @property-read \App\Models\User|null $head
 * @property-read \App\Models\EmployeeInformation|null $heads
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $officers_in_charge
 * @property-read int|null $officers_in_charge_count
 * @method static \Illuminate\Database\Eloquent\Builder|Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Office query()
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereAdminUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCampusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereHeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Office whereUpdatedAt($value)
 */
	class Office extends \Eloquent {}
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
 */
	class PhilippineCity extends \Eloquent {}
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
 */
	class PhilippineProvince extends \Eloquent {}
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
 */
	class PhilippineRegion extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeInformation[] $employee_informations
 * @property-read int|null $employee_informations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RelatedDocumentsList
 *
 * @property int $id
 * @property int $voucher_sub_type_id
 * @property array $documents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VoucherSubType|null $voucher_sub_type
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList query()
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RelatedDocumentsList whereVoucherSubTypeId($value)
 */
	class RelatedDocumentsList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $code
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeInformation[] $employee_informations
 * @property-read int|null $employee_informations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
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
 */
	class Sidenote extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $applicants
 * @property-read int|null $applicants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Itinerary[] $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \App\Models\PhilippineCity|null $philippine_city
 * @property-read \App\Models\PhilippineProvince|null $philippine_province
 * @property-read \App\Models\PhilippineRegion|null $philippine_region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sidenote[] $sidenotes
 * @property-read int|null $sidenotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $signatories
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
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereOtherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePhilippineRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereRegistrationAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereTravelOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TravelOrder whereUpdatedAt($value)
 */
	class TravelOrder extends \Eloquent {}
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
 */
	class TravelOrderType extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucher[] $disbursement_vouchers
 * @property-read int|null $disbursement_vouchers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DisbursementVoucher[] $disbursement_vouchers_to_sign
 * @property-read int|null $disbursement_vouchers_to_sign_count
 * @property-read \App\Models\EmployeeInformation|null $employee_information
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Itinerary[] $itineraries
 * @property-read int|null $itineraries_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Office|null $office_administered
 * @property-read \App\Models\Office|null $office_headed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $offices_in_charge
 * @property-read int|null $offices_in_charge_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sidenote[] $sidenotes
 * @property-read int|null $sidenotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TravelOrder[] $travel_order_applications
 * @property-read int|null $travel_order_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TravelOrder[] $travel_order_signatories
 * @property-read int|null $travel_order_signatories_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
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
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser {}
}

namespace App\Models{
/**
 * App\Models\VoucherCategory
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VoucherType[] $voucher_types
 * @property-read int|null $voucher_types_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherCategory whereUpdatedAt($value)
 */
	class VoucherCategory extends \Eloquent {}
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
 * @property-read \App\Models\VoucherType|null $voucher_types
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherSubType whereVoucherTypeId($value)
 */
	class VoucherSubType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VoucherType
 *
 * @property int $id
 * @property int $voucher_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VoucherCategory|null $voucher_category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\VoucherSubType[] $voucher_subtypes
 * @property-read int|null $voucher_subtypes_count
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoucherType whereVoucherCategoryId($value)
 */
	class VoucherType extends \Eloquent {}
}

