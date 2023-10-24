<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use App\Forms\Components\Flatpickr;
use App\Models\EmployeeInformation;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use App\Models\PhilippineRegion;
use App\Models\Position;
use App\Models\RequestSchedule;
use App\Models\RequestScheduleTimeAndDate;
use App\Models\TravelOrder;
use App\Models\TravelOrderType;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Carbon;
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

    public $date_and_time = [];

    public $travel_date;

    protected function getFormSchema(): array
    {
        return [
            Toggle::make('is_travel_order')
                ->label('Import Travel Order')
                ->onIcon('heroicon-s-check')
                ->disabled(fn ($record) => request()->integer('travel_order'))
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
                ->required()->disabled(fn ($record) => request()->integer('travel_order'))
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
                ->options(EmployeeInformation::pluck('full_name', 'user_id'))->disabled(fn ($record) => request()->integer('travel_order')),
            Textarea::make('purpose')
                ->required()->disabled(fn ($record) => request()->integer('travel_order')),
            Fieldset::make('Destination')->schema([
                Grid::make(2)->schema([
                    Select::make('region_code')
                        ->reactive()
                        ->label('Region')
                        ->required()
                        ->options(PhilippineRegion::pluck('region_description', 'region_code'))->disabled(fn ($record) => request()->integer('travel_order')),
                    Select::make('province_code')
                        ->reactive()
                        ->label('Province')
                        ->visible(fn ($get) => $get('region_code'))
                        ->required()
                        ->options(fn ($get) => PhilippineProvince::where('region_code', $get('region_code'))->pluck('province_description', 'province_code'))
                        ->disabled(fn ($record) => request()->integer('travel_order')),
                    Select::make('city_code')
                        ->label('City')
                        ->visible(fn ($get) => $get('province_code'))
                        ->required()
                        ->options(fn ($get) => PhilippineCity::where('province_code', $get('province_code'))->pluck('city_municipality_description', 'city_municipality_code'))
                        ->disabled(fn ($record) => request()->integer('travel_order')),
                    TextInput::make('other_details')->nullable()->disabled(fn ($record) => request()->integer('travel_order')),
                ]),
            ]),
            Grid::make(2)->schema([
                Flatpickr::make('date_of_travel_from')
                    ->disableTime()
                    ->reactive()
                    ->afterStateUpdated(function ($get, $state, $set) {
                            if($state != null && $this->date_of_travel_to != null)
                            {
                                $dates = $this->getDateRange($state, $get('date_of_travel_to'));
                                $set('date_and_time', $dates);
                            }
                    })
                    ->required()
                    ->disabled(fn ($record) => request()->integer('travel_order')),
                Flatpickr::make('date_of_travel_to')
                    ->disableTime()
                    ->reactive()
                    ->afterStateUpdated(function ($get, $state, $set) {
                        if($this->date_of_travel_from != null && $state != null)
                        {
                            $dates = $this->getDateRange($get('date_of_travel_from'), $state);
                            $set('date_and_time', $dates);
                        }
                    })
                    ->afterOrEqual(fn ($get) => $get('date_of_travel_from'))
                    ->required()
                    ->disabled(fn ($record) => request()->integer('travel_order')),
            ]),
            Repeater::make('date_and_time')
            ->label('Assign time to each date')
            ->schema([
                DatePicker::make('date')
                ->disabled()
                ->reactive()
                ->required(),
                Repeater::make('time')
                ->schema([
                    Grid::make(2)
                    ->schema([
                        Flatpickr::make('time_from')
                        ->label('From')
                        ->disableDate()
                        ->reactive()
                        ->required(),
                        Flatpickr::make('time_to')
                        ->label('To')
                        ->disableDate()
                        ->reactive()
                        ->required(),
                    ])
                ])->createItemButtonLabel('Add time')
            ])->visible(fn ($get) => $this->date_of_travel_from != null && $this->date_of_travel_to != null)
            ->reactive()->disableItemCreation(),
            // ->disableItemDeletion(),
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

                }),

            Select::make('vehicle_id')
                ->label('Vehicle')
                ->options(Vehicle::select(DB::raw("CONCAT(campuses.name, ' - ', vehicles.model) AS value"), 'vehicles.id')
                ->join('campuses', 'campuses.id', '=', 'vehicles.campus_id')
                ->pluck('value', 'id'))
                ->searchable()
                ->reactive()
                ->visible(fn ($get) => $get('is_vehicle_preferred') == true)
                ->required(),
        ];
    }

    public function mergeDateAndTime($date_and_time)
    {
        $result = [];

        foreach ($date_and_time as $item) {
            $date = $item['date'];
            $hasTime = false;

            if (isset($item['time'])) {
                foreach ($item['time'] as $timeId => $time) {
                    $result[] = [
                        'date' => $date,
                        'time_from' => $time['time_from'],
                        'time_to' => $time['time_to'],
                    ];
                    $hasTime = true;
                }
            }

            if (!$hasTime) {
                $result[] = [
                    'date' => $date,
                    'time_from' => null,
                    'time_to' => null,
                ];
            }
        }

        return $result;
    }

    public function save()
    {
        $dates_and_time = $this->mergeDateAndTime($this->date_and_time);
        $has_no_time = false;
        foreach ($dates_and_time as $item) {
            if (!isset($item['time_from']) || !isset($item['time_to'])) {
                $has_no_time = true;
            }
        }

        // $this->validate([
        //     'date_of_travel_to' => 'required',
        // ]);
        if (in_array(auth()->user()->id, $this->passengers)) {
            if ($this->date_of_travel_from > $this->date_of_travel_to) {
                Notification::make()->title('Operation Failed')->body('Invalid Dates. Please Check again')->danger()->send();
            }elseif($has_no_time)
            {
                Notification::make()->title('Operation Failed')->body('Time must be filled. Please add time to each date.')->danger()->send();
            }
             else {

                $dates_and_time = $this->mergeDateAndTime($this->date_and_time);
                $hasConflictTime = false;
                $hasConflictVehicle = false;
                $vehicleId = $this->vehicle_id;
                // $conflictTime;
                // $conflictVehicle;

                foreach ($dates_and_time as $item) {
                    if($this->vehicle_id == null )
                    {
                        $conflict = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) {
                            $query->where('status', 'Approved');
                        })
                        ->where('travel_date', $item['date'])
                        ->where(function ($query) use ($item) {
                            $query->where(function ($query) use ($item) {
                                $query->where('time_from', '<', $item['time_to'])
                                      ->where('time_to', '>', $item['time_from']);
                            })->orWhere(function ($query) use ($item) {
                                $query->where('time_from', '>=', $item['time_from'])
                                      ->where('time_to', '<=', $item['time_to']);
                            });
                        })
                        ->first();

                        if($conflict)
                        {
                            $hasConflictTime = true;
                        }
                    }else{
                        $conflict = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId){
                            $query->where('status', 'Approved')->where('vehicle_id', $vehicleId);
                        })
                        ->where('travel_date', $item['date'])
                        ->where(function ($query) use ($item) {
                            $query->where(function ($query) use ($item) {
                                $query->where('time_from', '<', $item['time_to'])
                                      ->where('time_to', '>', $item['time_from']);
                            })->orWhere(function ($query) use ($item) {
                                $query->where('time_from', '>=', $item['time_from'])
                                      ->where('time_to', '<=', $item['time_to']);
                            });
                        })
                        ->first();

                        if($conflict)
                        {
                            $hasConflictVehicle = true;
                        }
                    }

                    if($conflict)
                    {
                        if($hasConflictTime)
                        {
                            $date = Carbon::parse($item['date'])->format('F d, Y');
                            Notification::make()->title('Operation Failed')->body("The date {$date} has a conflict in the approved schedules")->danger()->send();
                            return;
                        }elseif($hasConflictVehicle)
                        {
                            $date = Carbon::parse($item['date'])->format('F d, Y');
                            Notification::make()->title('Operation Failed')->body("The vehicle you chose has a conflict in the approved schedules. Date : {$date}")->danger()->send();
                            return;
                        }


                        // $hasConflict = true;
                        // $date = Carbon::parse($item['date'])->format('F d, Y');
                        // Notification::make()->title('Operation Failed')->body("The date {$date} has a conflict in the approved schedules")->danger()->send();
                        // return;
                    }
                }

                if (!$conflict) {
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
                        // 'travel_dates' => json_encode($dates),
                        // 'available_travel_dates' => json_encode($dates),
                        // 'time_start' => $this->time_start,
                        // 'time_end' => $this->time_end,
                        'status' => 'Pending',
                    ]);
                    $rq->applicants()->sync($this->passengers);

                    foreach ($dates_and_time as $item) {
                        RequestScheduleTimeAndDate::create([
                            'request_schedule_id' => $rq->id,
                            'vehicle_id' => $this->vehicle_id == null ? null : $this->vehicle_id,
                            'travel_date' => $item['date'],
                            'time_from' => $item['time_from'],
                            'time_to' => $item['time_to'],
                        ]);
                    }
                    DB::commit();
                     Notification::make()->title('Operation Success')->body('Request has been created.')->success()->send();
                    return redirect()->route('requisitioner.motorpool.index');

                }
            }
        } else {
            Notification::make()->title('Operation Failed')->body('Passengers must include you.')->danger()->send();
        }
    }

    function getDateRange($startDate, $endDate) {
        $dates = [];
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = ['date' => date('Y-m-d', $currentDate)];
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dates;
    }

    public function mount()
    {
        $this->passengers = [auth()->id()];
        $this->form->fill();

        if (request()->integer('travel_order')) {
            $travel_order = auth()->user()->travel_order_applications()->find(request()->integer('travel_order'));
            if ($travel_order) {
                $dates = $this->getDateRange($travel_order->date_from, $travel_order->date_to);
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
                $this->date_and_time =  $dates;
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
