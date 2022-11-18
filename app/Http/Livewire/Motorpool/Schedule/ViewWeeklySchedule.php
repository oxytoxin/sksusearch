<?php

namespace App\Http\Livewire\Motorpool\Schedule;

use App\Models\RequestSchedule;
use App\Models\Vehicle;
use Carbon\Carbon;
use DateTime;
use Filament\Notifications\Notification;
use Livewire\Component;

class ViewWeeklySchedule extends Component
{
    public $today;
    public $currentWeekNumber;
    public $currentYearNumber;
    public $currentMonth;
    public $dates;
    public $numberOfWeeksThisYear;
    public $currentVehicle;
    public $vehicles;
    public $schedules;
    public function nextWeek()
    {
        $this->currentWeekNumber +=1;
        if ($this->currentWeekNumber > $this->numberOfWeeksThisYear) {
            $this->currentWeekNumber = 1;
            $this->currentYearNumber += 1;
            $this->numberOfWeeksThisYear =new DateTime('December 28th, '.$this->currentYearNumber);
            $this->numberOfWeeksThisYear =$this->numberOfWeeksThisYear->format('W');
        }
        
        if ($this->currentWeekNumber <= 9) {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
        } else {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
        }
        
        $this->setDates();
    }
    
    public function previousWeek()
    {
        $this->currentWeekNumber -=1;
        if ($this->currentWeekNumber < 1 ) {
            $this->currentYearNumber -= 1;
            $this->numberOfWeeksThisYear =new DateTime('December 28th, '.$this->currentYearNumber);
            $this->currentWeekNumber = $this->numberOfWeeksThisYear->format('W');
            $this->numberOfWeeksThisYear =$this->numberOfWeeksThisYear->format('W');
        }
        if ($this->currentWeekNumber <= 9) {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
        } else {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
        }
        $this->setDates();
    }
   
    public function currentweek()
    {
        $this->currentWeekNumber = $this->today->format('W');
        $this->currentYearNumber = $this->today->format('Y');
        if ($this->currentWeekNumber <= 9) {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
        } else {
            $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
        }
        $this->setDates();
    }
    
    public function setDates()
    {
        for ($i=0; $i < count($this->dates); $i++) { 
            // $tempDate = date('d',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
            $tempDate = date('d',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
            $untilDate =date('t',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
            $tempmonth = date('m',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
                // dd($tempmonth);
            if ($this->currentWeekNumber <= 9) {
                $tempDate = date('d',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
                $untilDate =date('t',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
                $tempmonth = date('m',strtotime($this->currentYearNumber.'W0'. $this->currentWeekNumber));
            }            
           
            if($tempDate+$i > $untilDate){
               $this->dates[$i] = ['full_date' => $this->currentYearNumber.'-'.$tempmonth.'-'.$tempDate+$i - $untilDate,'day_date'=>$tempDate+$i - $untilDate];
            }else{
                $this->dates[$i] = ['full_date' => $this->currentYearNumber.'-'.$tempmonth.'-'.$tempDate+$i,'day_date'=>$tempDate+$i];
            }
        }
        $this->getSchedule();
    }

    
    public function setVehicle($id)
    {
        
        $this->currentVehicle=Vehicle::where('id',$id)->first();
        $this->getSchedule();
    }
    
    public function getSchedule()
    {
       if($this->currentVehicle == null){
        
       }else{
        $this->schedules=RequestSchedule::where('vehicle_id',$this->currentVehicle->id)
        ->whereBetween('date_of_travel',
            [
            strval((new Carbon($this->dates[0]['full_date']))->format('Y-m-d')),
            strval((new Carbon($this->dates[6]['full_date']))->format('Y-m-d'))
            ]
            )
        ->get();        

       }
    }
    
    public function mount()
    {
        $this->today=now();
        $this->currentWeekNumber = $this->today->format('W');
        $this->currentYearNumber = $this->today->format('Y');
        $this->numberOfWeeksThisYear =new DateTime('December 28th, '.$this->currentYearNumber);
        $this->numberOfWeeksThisYear =$this->numberOfWeeksThisYear->format('W');
        $this->currentMonth =  date('F',strtotime($this->currentYearNumber.'W'. $this->currentWeekNumber));
        $this->dates = array_fill(0, 7, "0");
        $this->vehicles=Vehicle::all();
        $this->currentVehicle=Vehicle::first();
        $this->schedules=RequestSchedule::all();
        $this->setDates();       
        $this->getSchedule();
    }

    
    public function redirectToCreateVehicle()
    {
        
        redirect()->route('motorpool.vehicle.create',['1']);
    }

    
    
    public function render()
    {
        return view('livewire.motorpool.schedule.view-weekly-schedule');
    }
}
