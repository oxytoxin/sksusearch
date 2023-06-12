<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Forms\Components\Flatpickr;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use App\Models\RequestSchedule;
use App\Models\RequestScheduleTimeAndDate;
use App\Models\EmployeeInformation;
use App\Models\Position;
use App\Models\Vehicle;
use Livewire\Component;
use WireUi\Traits\Actions;
use DB;

class RequestVehicleShow extends Component implements HasForms
{
    use InteractsWithForms;
    use Actions;
    public $request;
    public $request_schedule;
    public $request_schedule_date_and_time;
    public $travel_dates;
    public $available_travel_dates;
    public $remarks;
    public $driverss;
    public $vehicless;
    public $assign_vehicle;
    public $change_vehicle;
    public $time_start;
    public $time_end;
    public $travelDates = [];
    public $date_and_time = [];
    public $scheduleTimes;

    //modals
    public $modifyDates = false;
    public $rejectModal = false;
    public $assignDriverModal = false;
    public $assignVehicleModal = false;
    public $modifyVehicleModal = false;

    protected $rules = [
        'vehicless' => 'required',
        'driverss' => 'required',
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($request)
    {
        $this->form->fill();
        $this->scheduleTimes = RequestScheduleTimeAndDate::where('request_schedule_id', $request)->get();
        foreach ($this->scheduleTimes as $scheduleTime) {
            $date = $scheduleTime->travel_date;
            $timeFrom = $scheduleTime->time_from;
            $timeTo = $scheduleTime->time_to;

            // Generate unique keys for the date and time slot
            $dateKey = uniqid();
            $timeSlotKey = uniqid();

            $data[$dateKey] = [
                'date' => $date, // Convert to a string in 'Y-m-d' format
                'time' => [
                    $timeSlotKey => [
                        'time_from' => $timeFrom,
                        'time_to' => $timeTo,
                    ],
                ],
            ];
        }
          //dd($data);

        $this->date_and_time = $data;
        $this->request = RequestSchedule::find($request);
        $this->travel_dates = $this->request->travel_dates;
        $this->available_travel_dates = $this->request->available_travel_dates;

        $availableTravelDates = json_decode($this->request->available_travel_dates);
        // Set the initial state of the checkboxes to checked only if the date is in the available travel dates
        // foreach (json_decode($this->travel_dates) as $travelDate) {
        //     $this->travelDates[$travelDate] = in_array($travelDate, $availableTravelDates);
        // }
    }

    public function storeData()
    {
    $data = [];

    foreach ($this->scheduleTimes as $scheduleTime) {
        $date = $scheduleTime->travel_date;
        $timeFrom = $scheduleTime->time_from;
        $timeTo = $scheduleTime->time_to;

        // Generate a unique key for each time slot
        $timeSlotKey = uniqid();

        $data['date'] = $date;
        $data['time'][$timeSlotKey] = [
            'time_from' => $timeFrom,
            'time_to' => $timeTo,
        ];
    }

    // Do something with the $data array
    // For example, you can save it to the database or perform any other operations.

    dd($data); // Display the data for testing purposes
    }

    protected function getFormSchema(): array
    {
        return [
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
            ])->reactive()->disableItemCreation(),
        ];
    }

    public function approveRequest($id)
    {
        $this->request_schedule = RequestSchedule::find($id);
        $this->dialog()->confirm([
            'title'       => 'Are you sure you want to approve this request?',
            'acceptLabel' => 'Yes, approve it',
            'method'      => 'confirmApprove',
            'params'      => 'Saved',
        ]);

    }

    public function confirmApprove()
    {
        $this->request_schedule->status = 'Approved';
        $this->request_schedule->approved_at = \Carbon\Carbon::parse(now())->format('Y-m-d H:i:s');
        $this->request_schedule->save();
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Request for vehicle has been approved'
        );
        return redirect()->route('signatory.motorpool.signed');
    }

    public function rejectRequest($id)
    {
        $this->request_schedule = RequestSchedule::find($id);
        $this->validate([
            'remarks' => 'required',
        ]);
        $this->dialog()->confirm([
            'title'       => 'Are you sure you want to reject this request?',
            'acceptLabel' => 'Yes, reject it',
            'method'      => 'confirmReject',
            'params'      => 'Saved',
        ]);
    }

    public function confirmReject()
    {
        $this->request_schedule->status = 'Rejected';
        $this->request_schedule->remarks = $this->remarks;
        $this->request_schedule->rejected_at = \Carbon\Carbon::parse(now())->format('Y-m-d H:i:s');
        $this->request_schedule->save();
        $this->dialog()->success(
            $title = 'Success',
            $description = 'Request for vehicle has been rejected'
        );
        return redirect()->route('signatory.motorpool.signed');
    }

    public function assignVehicle($id)
    {
        $this->request_schedule = RequestSchedule::find($id);
        $this->request_schedule_date_and_time = RequestScheduleTimeAndDate::where('request_schedule_id', $id)->get();
        $this->validate([
            'assign_vehicle' => 'required',
        ]);
        $this->dialog()->confirm([
            'title'       => 'Are you sure you want to assign this vehicle?',
            'acceptLabel' => 'Yes',
            'method'      => 'confirmVehicle',
            'params'      => 'Saved',
        ]);
    }

    public function confirmVehicle()
    {
            DB::beginTransaction();

            $vehicleId = $this->assign_vehicle;

            foreach($this->request_schedule_date_and_time as $item)
            {
                $conflict = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId){
                    $query->where('status', 'Approved')->where('vehicle_id', $vehicleId);
                })
                ->where('travel_date', $item->travel_date)
                ->where(function ($query) use ($item) {
                    $query->where(function ($query) use ($item) {
                        $query->where('time_from', '<', $item->time_to)
                              ->where('time_to', '>', $item->time_from);
                    })->orWhere(function ($query) use ($item) {
                        $query->where('time_from', '>=', $item->time_from)
                              ->where('time_to', '<=', $item->time_to);
                    });
                })
                ->first();

                if(!$conflict)
                {
                    $this->request_schedule->vehicle_id = $this->assign_vehicle;
                    $this->request_schedule->save();
                    $item->vehicle_id = $this->assign_vehicle;
                    $item->save();

                    $this->dialog()->success(
                        $title = 'Success',
                        $description = 'Vehicle is assigned'
                    );
                    $this->assignVehicleModal = false;
                    $this->emit('refreshComponent');
                }else{
                    $this->dialog()->error(
                        $title = 'Vehicle Unavailable',
                        $description = 'Vehicle has an existing schedule with this records date and time.'
                    );
                }

            }
            DB::commit();

    }

    public function changeVehicle($id)
    {
        $this->request_schedule = RequestSchedule::find($id);
        $this->request_schedule_date_and_time = RequestScheduleTimeAndDate::where('request_schedule_id', $id)->get();

        $this->validate([
            'change_vehicle' => 'required',
        ]);
        $this->dialog()->confirm([
            'title'       => 'Are you sure you want to assign this vehicle?',
            'acceptLabel' => 'Yes',
            'method'      => 'confirmChangeVehicle',
            'params'      => 'Saved',
        ]);
    }

    public function confirmChangeVehicle()
    {

        DB::beginTransaction();

        $vehicleId = $this->change_vehicle;

        foreach($this->request_schedule_date_and_time as $item)
        {
            $conflict = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId){
                $query->where('status', 'Approved')->where('vehicle_id', $vehicleId);
            })
            ->where('travel_date', $item->travel_date)
            ->where(function ($query) use ($item) {
                $query->where(function ($query) use ($item) {
                    $query->where('time_from', '<', $item->time_to)
                          ->where('time_to', '>', $item->time_from);
                })->orWhere(function ($query) use ($item) {
                    $query->where('time_from', '>=', $item->time_from)
                          ->where('time_to', '<=', $item->time_to);
                });
            })
            ->first();

            if(!$conflict)
            {
                $this->request_schedule->vehicle_id = $this->change_vehicle;
                $this->request_schedule->save();
                $item->vehicle_id = $this->change_vehicle;
                $item->save();

                $this->dialog()->success(
                    $title = 'Success',
                    $description = 'Vehicle is updated'
                );
                $this->modifyVehicleModal = false;
                $this->emit('refreshComponent');
            }else{
                $this->dialog()->error(
                    $title = 'Vehicle Unavailable',
                    $description = 'Vehicle has an existing schedule with this records date and time.'
                );
            }

        }
        DB::commit();




    }

    public function assignDriver($id)
    {
        $this->request_schedule = RequestSchedule::find($id);
        $this->validate([
            'driver' => 'required',
        ]);
        $this->dialog()->confirm([
            'title'       => 'Are you sure you want to assign this driver?',
            'acceptLabel' => 'Yes',
            'method'      => 'confirmDriver',
            'params'      => 'Saved',
        ]);
    }

    public function confirmDriver()
    {
            $this->request_schedule->driver_id = $this->driver->first()->id;
            $this->request_schedule->save();
            $this->dialog()->success(
                $title = 'Success',
                $description = 'Driver is assigned'
            );
            return redirect()->route('motorpool.request.index');
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


    public function updateTravelDates()
    {
        $dates_and_time = $this->mergeDateAndTime($this->date_and_time);
        $this->request_schedule_date_and_time = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query){
            $query->where('status', 'Approved')->where('vehicle_id', $this->request->vehicle_id);
        })->get();
        $existingDates = $this->request_schedule_date_and_time->pluck('travel_date')->toArray();
        $updatedDates = collect($this->date_and_time)->pluck('date')->toArray();
        $datesToRemove = array_diff($existingDates, $updatedDates);
        $vehicleId = $this->request->vehicle_id;
        // if (!empty($datesToRemove)) {
        // }
        DB::beginTransaction();
        RequestScheduleTimeAndDate::where('request_schedule_id', $this->request->id)->delete();
        foreach($this->request_schedule_date_and_time as $item)
        {
            $conflict = RequestScheduleTimeAndDate::whereHas('request_schedule', function ($query) use ($vehicleId){
                $query->where('status', 'Approved')->where('vehicle_id', $vehicleId);
            })
            ->where('travel_date', $item->travel_date)
            ->where(function ($query) use ($item) {
                $query->where(function ($query) use ($item) {
                    $query->where('time_from', '<', $item->time_to)
                          ->where('time_to', '>', $item->time_from);
                })->orWhere(function ($query) use ($item) {
                    $query->where('time_from', '>=', $item->time_from)
                          ->where('time_to', '<=', $item->time_to);
                });
            })
            ->first();
            if(!$conflict)
            {
                // Insert or update the remaining dates
                foreach ($dates_and_time as $item) {
                    RequestScheduleTimeAndDate::updateOrCreate(
                        [
                            'request_schedule_id' => $this->request->id,
                            'travel_date' => $item['date'],
                        ],
                        [
                            'vehicle_id' =>  $vehicleId,
                            'time_from' => $item['time_from'],
                            'time_to' => $item['time_to'],
                        ]
                    );
                }
            }else{
                $date = \Carbon\Carbon::parse($conflict->travel_date)->format('F d, Y');
                $time_from = \Carbon\Carbon::parse($conflict->time_from)->format('h:i A');
                $time_to = \Carbon\Carbon::parse($conflict->time_to)->format('h:i A');
                $this->dialog()->error(
                    $title = 'Operation Failed',
                    $description = "The date {$date} - ({$time_from} to  {$time_to}) has a conflict in the approved schedules"
                );
                return;
            }



        }
        DB::commit();

        $this->dialog()->success(
            $title = 'Success',
            $description = 'Available dates updated'
        );

        $this->modifyDates = false;
    }

    public function render()
    {
        $this->driver = EmployeeInformation::where('position_id', Position::where('description', 'Driver')->pluck('id'))
        ->whereHas('office', function ($query) {
            return $query->where('campus_id', '=', auth()->user()->employee_information->office->campus_id);
        })->get();
        //$this->vehicles = Vehicle::where('campus_id', auth()->user()->employee_information->office->campus_id)->get();
        return view('livewire.requisitioner.motorpool.request-vehicle-show', [
             'vehicles' =>  Vehicle::where('campus_id', auth()->user()->employee_information->office->campus_id)->get(),
             'vehicles_for_update' =>  Vehicle::where('campus_id', auth()->user()->employee_information->office->campus_id)->whereNotIn('id', [$this->request->vehicle_id])->get(),
            'drivers' => $this->driverss,
        ]);
    }
}
