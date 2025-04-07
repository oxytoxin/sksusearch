<?php

namespace App\Http\Livewire\Motorpool\Schedule;

use Livewire\Component;
use App\Models\RequestSchedule;
use App\Models\RequestScheduleTimeAndDate;
use App\Models\Vehicle;
use Carbon\Carbon;

class ViewSchedules extends Component
{
    public $events = [];
    public $vehicles = [];
    public $vehicle;
    public $year;
    public $month;
    public $is_driver = false;


    protected $rules = [
        'vehicles' => 'required',
    ];
    public function mount($year = null, $month = null, $vehicle = null)
    {
        $this->vehicles = Vehicle::get();
        $this->events = $this->getFormattedEvents();
        $this->year = $year ?? now()->year;
        $this->month = $month ?? now()->month;
        $this->vehicle = $vehicle ?? null;
        $this->is_driver = auth()->user()->employee_information->position_id == 28;
    }

    public function updatedVehicle($value)
    {
        $this->dispatchBrowserEvent('refreshCalendar', [
            'events' => $this->getFormattedEvents()
        ]);
    }

    private function getFormattedEvents()
    {
        $events = RequestScheduleTimeAndDate::query()->whereHas('request_schedule', function ($query) {
            $query->where('status', 'Approved')->whereNotNull('vehicle_id')
            ->when($this->vehicle, function ($query) {
            $query->where('vehicle_id', $this->vehicle);
            })
            ->when($this->is_driver, function ($query) {
            $query->where('driver_id', auth()->user()->id);
            });
        })->get();

        $formattedEvents = [];
        foreach ($events as $event) {
            // $passengers =  $event->request_schedule->applicants->pluck('employee_information.full_name')->toArray();
            $startDateTime = date('Y-m-d H:i', strtotime($event->travel_date . ' ' . $event->time_from));
            $endDateTime = date('Y-m-d H:i', strtotime($event->travel_date . ' ' . $event->time_to));
            $formattedEvents[] = [
                'title' => $event->request_schedule->other_details != null ? $event->request_schedule->other_details . ', ' . $event->request_schedule->philippine_city->city_municipality_description . ', ' .
                            $event->request_schedule->philippine_province->province_description . ', ' . $event->request_schedule->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_from)) . ' - ' . date('g:i A', strtotime($event->time_to)) . ')' :
                            $event->request_schedule->philippine_city->city_municipality_description . ', ' .
                            $event->request_schedule->philippine_province->province_description . ', ' . $event->request_schedule->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_from)) . ' - ' . date('g:i A', strtotime($event->time_to)) . ')',
                'start' =>  $startDateTime,
                'end' => $endDateTime,
                'purpose' => $event->request_schedule->purpose,
                'vehicle' => $event->request_schedule->vehicle->model,
                'plate_number' => $event->request_schedule->vehicle->plate_number,
                'campus' => $event->request_schedule->vehicle->campus->name,
                'driver' => $event->request_schedule->driver == null ?  'No Driver Assigned' :  $event->request_schedule->driver->full_name,
                // 'passengers' => $passengers,
                'requisitioner' => $event->request_schedule->requested_by->employee_information->full_name,
                        ];

        }
        return $formattedEvents;
    }


    public function render()
    {
        return view('livewire.motorpool.schedule.view-schedules');
    }
}
