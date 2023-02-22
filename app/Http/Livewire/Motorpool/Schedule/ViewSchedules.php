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
            ->whereNotNull('driver_id')
            ->when($this->vehicle, function ($query) {
                $query->where('vehicle_id', $this->vehicle);
            })
            ->get();
        $formattedEvents = [];
        foreach ($events as $event) {
            $startDateTime = date('Y-m-d H:i', strtotime($event->date_of_travel_from . ' ' . $event->time_start));
            $endDateTime = date('Y-m-d H:i', strtotime($event->date_of_travel_to . ' ' . $event->time_end));
            $formattedEvents[] = [
                'title' => $event->other_details != null ? $event->other_details . ', ' . $event->philippine_city->city_municipality_description . ', ' .
                    $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')' :
                    $event->philippine_city->city_municipality_description . ', ' .
                    $event->philippine_province->province_description . ', ' . $event->philippine_region->region_description . ' (' . date('g:i A', strtotime($event->time_start)) . ' - ' . date('g:i A', strtotime($event->time_end)) . ')',
                'start' =>  $startDateTime,
                'end' => $endDateTime,
                'purpose' => $event->purpose,
            ];
        }
        return $formattedEvents;
    }


    public function render()
    {
        return view('livewire.motorpool.schedule.view-schedules');
    }
}
