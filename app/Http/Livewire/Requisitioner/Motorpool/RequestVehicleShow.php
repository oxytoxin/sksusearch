<?php

namespace App\Http\Livewire\Requisitioner\Motorpool;

use App\Models\RequestSchedule;
use App\Models\EmployeeInformation;
use App\Models\Position;
use App\Models\Vehicle;
use Livewire\Component;
use WireUi\Traits\Actions;

class RequestVehicleShow extends Component
{
    use Actions;
    public $request;
    public $request_schedule;
    public $travel_dates;
    public $available_travel_dates;
    public $remarks;
    public $driverss;
    public $vehicless;
    public $time_start;
    public $time_end;
    public $travelDates = [];

    //modals
    public $modifyDates = false;
    public $rejectModal = false;
    public $assignDriverModal = false;
    public $assignVehicleModal = false;

    protected $rules = [
        'vehicless' => 'required',
        'driverss' => 'required',
    ];

    public function mount($request)
    {
        $this->request = RequestSchedule::find($request);
        $this->travel_dates = $this->request->travel_dates;
        $this->available_travel_dates = $this->request->available_travel_dates;

        $availableTravelDates = json_decode($this->request->available_travel_dates);
        // Set the initial state of the checkboxes to checked only if the date is in the available travel dates
        foreach (json_decode($this->travel_dates) as $travelDate) {
            $this->travelDates[$travelDate] = in_array($travelDate, $availableTravelDates);
        }
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
        $this->validate([
            'vehicles' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
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
        if($this->time_start < $this->time_end)
        {
            $this->request_schedule->vehicle_id = $this->vehicles->first()->id;
            $this->request_schedule->time_start = $this->time_start;
            $this->request_schedule->time_end = $this->time_end;
            $this->request_schedule->save();
            $this->dialog()->success(
                $title = 'Success',
                $description = 'Vehicle is assigned'
            );
            return redirect()->route('motorpool.request.index');
        }else{
            $this->dialog()->error(
                $title = 'Failed',
                $description = 'Invalid time'
            );
        }

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

    public function updateTravelDates()
    {
        $selectedCheckboxValues = array_keys(array_filter($this->travelDates));
        $this->request->update([
            'available_travel_dates' => json_encode($selectedCheckboxValues)
        ]);

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
        $this->vehicles = Vehicle::where('campus_id', auth()->user()->employee_information->office->campus_id)->get();
        return view('livewire.requisitioner.motorpool.request-vehicle-show', [
            'vehicles' => $this->vehicless,
            'drivers' => $this->driverss,
        ]);
    }
}
