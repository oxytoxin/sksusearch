<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use App\Forms\Components\Flatpickr;
use App\Models\EmployeeInformation;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use App\Models\PhilippineRegion;
use App\Models\Position;
use App\Models\RequestSchedule;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Toggle;
use Livewire\Component;
use WireUi\Traits\Actions;

class RequestVehicleCreate extends Component implements HasForms
{
    use InteractsWithForms;
    use Actions;

    public $is_travel_order;

    public $request_type;

    public $travel_order_id;

    public $driver_id;

    public $vehicle_id;

    public $passengers = [];

    public $purpose;

    public $region_code;

    public $province_code;

    public $city_code;

    public $other_details;

    public $date_of_travel_from;

    public $date_of_travel_to;

    public $is_vehicle_preferred;

    public $time_start;

    public $time_end;

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('is_travel_order')
                ->label('Import Travel Order')
                ->onIcon('heroicon-s-check')
                ->afterStateUpdated(function ($set, $state) {
                    if (!$state) {
                        $set('travel_order_id', '');
                        $set('purpose', '');
                        $set('region_code', '');
                        $set('province_code', '');
                        $set('city_code', '');
                        $set('other_details', '');
                        $set('date_of_travel', '');
                    }
                })->reactive(),
            Select::make('travel_order_id')
                ->label('Travel Order')
                ->searchable()
                ->preload()
                ->options(TravelOrder::whereHas('applicants', function ($query) {
                    $query->whereIn('user_id', [auth()->user()->id]);
                })
                    ->whereIn('travel_order_type_id', [TravelOrderType::OFFICIAL_BUSINESS, TravelOrderType::OFFICIAL_TIME])
                    ->pluck('tracking_code', 'id'))
                ->visible(fn ($get) => $get('is_travel_order') == true)
                ->required()
                ->afterStateUpdated(function ($set) {
                    $to = TravelOrder::find($this->travel_order_id);

                    foreach ($to->applicants as $applicant) {
                        array_push($this->passengers, $applicant->id);
                    }
                    $set('purpose', $to->purpose);
                    $set('region_code', $to->philippine_region->region_code);
                    $set('province_code', $to->philippine_province->province_code);
                    $set('city_code', $to->philippine_city->city_municipality_code);
                    $set('other_details', $to->other_details);
                    $set('date_of_travel_from', $to->date_from);
                    $set('date_of_travel_to', $to->date_to);
                    $set('passengers', $this->passengers);
                })
                ->reactive(),
            MultiSelect::make('passengers')
                ->required()
                ->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Textarea::make('purpose')
                ->required(),
            Fieldset::make('Destination')->schema([
                Grid::make(2)->schema([
                    Select::make('region_code')
                        ->reactive()
                        ->label('Region')
                        ->required()
                        ->options(PhilippineRegion::pluck('region_description', 'region_code')),
                    Select::make('province_code')
                        ->reactive()
                        ->label('Province')
                        ->visible(fn ($get) => $get('region_code'))
                        ->required()
                        ->options(fn ($get) => PhilippineProvince::where('region_code', $get('region_code'))->pluck('province_description', 'province_code')),
                    Select::make('city_code')
                        ->label('City')
                        ->visible(fn ($get) => $get('province_code'))
                        ->required()
                        ->options(fn ($get) => PhilippineCity::where('province_code', $get('province_code'))->pluck('city_municipality_description', 'city_municipality_code')),
                    TextInput::make('other_details')->nullable(),
                ]),
            ]),
            Grid::make(2)->schema([
                Flatpickr::make('date_of_travel_from')
                    ->disableTime()
                    ->reactive()
                    ->required(),
                Flatpickr::make('date_of_travel_to')
                    ->disableTime()
                    ->reactive()
                    ->afterOrEqual(fn ($get) => $get('date_of_travel_from'))
                    ->required(),
            ]),
            Toggle::make('is_vehicle_preferred')
                ->label('Select Vehicle')
                ->onIcon('heroicon-s-check')
                ->reactive()
                ->afterStateUpdated(function ($get, $state, $set) {
                    if ($state == false) {
                        $set('vehicle_id', null);
                        $set('time_start', null);
                        $set('time_end', null);
                    }
                    // $date_from = $get('date_of_travel_from');
                    // $date_to = $get('date_of_travel_to');
                    // if($date_from == null || $date_to == null)
                    // {
                    //     $this->dialog()->error(
                    //         $title = 'Invalid Dates',
                    //         $description = 'Travel dates must not be empty.'
                    //     );
                    //     $set('is_vehicle_preferred', false);
                    // }elseif($date_from > $date_to){
                    //     $this->dialog()->error(
                    //         $title = 'Invalid Dates',
                    //         $description = 'Date of travel from must not be greater than Date of travel to.'
                    //     );
                    //     $set('is_vehicle_preferred', false);
                    //     $set('date_of_travel_from', '');
                    //     $set('date_of_travel_to', '');
                    // }
                }),

            Select::make('vehicle_id')
                ->label('Vehicle')
                ->options(Vehicle::where('campus_id', auth()->user()->employee_information->office->campus_id)->pluck('model', 'id'))
                ->searchable()
                ->reactive()
                ->visible(fn ($get) => $get('is_vehicle_preferred') == true)
                ->required(),

            Grid::make(2)->schema([
                Flatpickr::make('time_start')
                    ->disableDate()
                    ->required(),
                Flatpickr::make('time_end')
                    ->disableDate()
                    ->afterOrEqual(fn ($get) => $get('time_start'))
                    ->required(),
            ])->visible(fn ($get) => $get('is_vehicle_preferred') == true),
        ];
    }

    public function save()
    {
        $this->validate([
            'date_of_travel_to' => 'required',
        ]);
        if (in_array(auth()->user()->id, $this->passengers)) {
            if ($this->date_of_travel_from > $this->date_of_travel_to) {
                Notification::make()->title('Operation Failed')->body('Invalid Dates. Please Check again')->danger()->send();
            } else {
                $dates = [];
                for ($date = \Carbon\Carbon::parse($this->date_of_travel_from); $date->lte(\Carbon\Carbon::parse($this->date_of_travel_to)); $date->addDay()) {
                    $dates[] = $date->format('Y-m-d');
                }
                if ($this->vehicle_id != null) {
                    $schedules = RequestSchedule::where('status', 'Approved')
                        ->where('vehicle_id', $this->vehicle_id)
                        ->where(function ($query) {
                            $query->whereJsonContains('travel_dates', $this->date_of_travel_from)
                                ->orWhereJsonContains('travel_dates', $this->date_of_travel_to);
                        })
                        ->get();
                } else {
                    $schedules = RequestSchedule::where('status', 'Approved')
                        ->where(function ($query) {
                            $query->whereJsonContains('travel_dates', $this->date_of_travel_from)
                                ->orWhereJsonContains('travel_dates', $this->date_of_travel_to);
                        })
                        ->get();
                }

                if ($schedules->isNotEmpty()) {
                    foreach ($schedules as $schedule) {
                        $available_dates = json_decode($schedule->available_travel_dates);
                        if (count(array_intersect($available_dates, $dates)) > 0) {
                            $this->dialog()->error(
                                $title = 'Invalid Dates',
                                $description = 'Travel dates has conflict with other schedules.'
                            );
                        } else {
                            DB::beginTransaction();
                            $rq = RequestSchedule::create([
                                'request_type' => $this->is_travel_order ? '1' : '0',
                                'travel_order_id' => $this->travel_order_id == '' ? null : $this->travel_order_id,
                                'driver_id' => $this->driver_id,
                                'requested_by_id' => auth()->user()->id,
                                'vehicle_id' => $this->vehicle_id,
                                'purpose' => $this->purpose,
                                'philippine_region_id' => PhilippineRegion::firstWhere('region_code', $this->region_code)?->id,
                                'philippine_province_id' => PhilippineProvince::firstWhere('province_code', $this->province_code)?->id,
                                'philippine_city_id' => PhilippineCity::firstWhere('city_municipality_code', $this->city_code)?->id,
                                'other_details' => $this->other_details,
                                'date_of_travel_from' => $this->date_of_travel_from,
                                'date_of_travel_to' => $this->date_of_travel_to,
                                'travel_dates' => json_encode($dates),
                                'available_travel_dates' => json_encode($dates),
                                'time_start' => $this->time_start,
                                'time_end' => $this->time_end,
                                'status' => 'Pending',
                            ]);
                            $rq->applicants()->sync($this->passengers);
                            DB::commit();

                            Notification::make()->title('Operation Success')->body('Request has been created.')->success()->send();
                            return redirect()->route('requisitioner.motorpool.show-request-form', $rq);
                        }
                    }
                } else {
                    DB::beginTransaction();
                    $rq = RequestSchedule::create([
                        'request_type' => $this->is_travel_order ? '1' : '0',
                        'travel_order_id' => $this->travel_order_id == '' ? null : $this->travel_order_id,
                        'driver_id' => $this->driver_id,
                        'requested_by_id' => auth()->user()->id,
                        'vehicle_id' => $this->vehicle_id,
                        'purpose' => $this->purpose,
                        'philippine_region_id' => PhilippineRegion::firstWhere('region_code', $this->region_code)?->id,
                        'philippine_province_id' => PhilippineProvince::firstWhere('province_code', $this->province_code)?->id,
                        'philippine_city_id' => PhilippineCity::firstWhere('city_municipality_code', $this->city_code)?->id,
                        'other_details' => $this->other_details,
                        'date_of_travel_from' => $this->date_of_travel_from,
                        'date_of_travel_to' => $this->date_of_travel_to,
                        'travel_dates' => json_encode($dates),
                        'available_travel_dates' => json_encode($dates),
                        'time_start' => $this->time_start,
                        'time_end' => $this->time_end,
                        'status' => 'Pending',
                    ]);
                    $rq->applicants()->sync($this->passengers);
                    DB::commit();

                    Notification::make()->title('Operation Success')->body('Request has been created.')->success()->send();
                    return redirect()->route('requisitioner.motorpool.show-request-form', $rq);
                }
            }
        } else {
            Notification::make()->title('Operation Failed')->body('Passengers must include you.')->danger()->send();
        }
    }

    public function mount()
    {
        $this->passengers = [auth()->id()];
        $this->form->fill();
        if (request()->integer('travel_order')) {
            $travel_order = auth()->user()->travel_order_applications()->find(request()->integer('travel_order'));
            if ($travel_order) {
                $this->is_travel_order = true;
                $this->travel_order_id = $travel_order->id;
                foreach ($travel_order->applicants as $applicant) {
                    array_push($this->passengers, $applicant->id);
                }
                $this->purpose = $travel_order->purpose;
                $this->region_code = $travel_order->philippine_region->region_code;
                $this->province_code = $travel_order->philippine_province->province_code;
                $this->city_code = $travel_order->philippine_city->city_municipality_code;
                $this->other_details = $travel_order->other_details;
                $this->date_of_travel_from = $travel_order->date_from;
                $this->date_of_travel_to = $travel_order->date_to;
                $this->passengers = $this->passengers;
                $this->is_vehicle_preferred = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.requisitioner.motorpool.request-vehicle-create');
    }
}
