<?php

namespace App\Http\Livewire\Motorpool\Schedule;

use Livewire\Component;
use App\Models\RequestSchedule;
use App\Models\Vehicle;

class ViewSchedules extends Component
{
    public $events = [];
    public $vehicles = [];
    public $vehicle;

    protected $rules = [
        'vehicles' => 'required',
    ];
    public function mount()
    {
        $this->vehicles = Vehicle::get();
        $this->events = $this->getFormattedEvents();
    }

    public function updatedVehicle($value)
    {
        $this->dispatchBrowserEvent('refreshCalendar', [
            'events' => $this->getFormattedEvents()
        ]);
    }

    private function getFormattedEvents()
    {
        $events = RequestSchedule::query()
            ->where('status', 'Approved')
            ->whereNotNull('vehicle_id')
            // ->whereNotNull('driver_id')
            ->when($this->vehicle, function ($query) {
                $query->where('vehicle_id', $this->vehicle);
            })
            ->get();
        $formattedEvents = [];
        foreach ($events as $event) {
            if($event->travel_dates === $event->available_travel_dates)
            {
                $startDateTime = date('Y-m-d H:i', strtotime($event->date_of_travel_from . ' ' . $event->time_start));
                $endDateTime = date('Y-m-d H:i', strtotime($event->date_of_travel_to . ' ' . $event->time_end));

                $passengers = $event->applicants->pluck('employee_information.full_name')->toArray();
                $formattedEvents[] = [
                    'title' => $event->other_details != null ? $event->other_details . ', ' . $event->philippine_city->city_municipality_description . ', ' .
                        $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')' :
                        $event->philippine_city->city_municipality_description . ', ' .
                        $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')',
                    'start' =>  $startDateTime,
                    'end' => $endDateTime,
                    'purpose' => $event->purpose,
                    'vehicle' => $event->vehicle->model,
                    'plate_number' => $event->vehicle->plate_number,
                    'driver' => $event->driver->full_name,
                    'passengers' => $passengers,
                    'requisitioner' => $event->requested_by->employee_information->full_name,
                ];
            }else{
                $availableDates = json_decode($event->available_travel_dates);
                $startDate = null;
                $endDate = null;
                $consecutiveDates = [];

                for($i = 0; $i < count($availableDates); $i++) {
                    $currentDate = $availableDates[$i];
                    $nextDate = isset($availableDates[$i+1]) ? $availableDates[$i+1] : null;

                    if($startDate == null) {
                        $startDate = $currentDate;
                        $endDate = $currentDate;
                    } else {
                        if(strtotime($nextDate) == strtotime('+1 day', strtotime($currentDate))) {
                            $endDate = $nextDate;
                        } else {
                            $consecutiveDates[] = [
                                'start' => $startDate,
                                'end' => $endDate
                            ];
                            $startDate = $currentDate;
                            $endDate = $currentDate;
                        }
                    }
                }

                // Store the last consecutive dates
                if($startDate != null && $endDate != null) {
                    $consecutiveDates[] = [
                        'start' => $startDate,
                        'end' => $endDate
                    ];
                }

                foreach($consecutiveDates as $consecutiveDate)
                {
                    $startDateTime = $consecutiveDate['start'];
                    $endDateTime = $consecutiveDate['end'];
                    $passengers = $event->applicants->pluck('employee_information.full_name')->toArray();
                    $formattedEvents[] = [
                        'title' => $event->other_details != null ? $event->other_details . ', ' . $event->philippine_city->city_municipality_description . ', ' .
                            $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')' :
                            $event->philippine_city->city_municipality_description . ', ' .
                            $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')',
                        'start' =>  $startDateTime,
                        'end' => $endDateTime,
                        'purpose' => $event->purpose,
                        'vehicle' => $event->vehicle->model,
                        'plate_number' => $event->vehicle->plate_number,
                        'driver' => $event->driver->full_name,
                        'passengers' => $passengers,
                        'requisitioner' => $event->requested_by->employee_information->full_name,
                    ];
                }


            }


        }
        return $formattedEvents;
    }


    public function render()
    {
        return view('livewire.motorpool.schedule.view-schedules');
    }
}
