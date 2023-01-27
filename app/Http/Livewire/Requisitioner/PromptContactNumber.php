<?php

namespace App\Http\Livewire\Requisitioner;

use Livewire\Component;
use App\Models\EmployeeInformation;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PromptContactNumber extends Component
{
    public $contact_number;
    public function render()
    {
        return view('livewire.requisitioner.prompt-contact-number');
    }

    public function saveNumber()
    {
        $this->validate([
            'contact_number' => ['required', 'numeric', 'regex:/^09[0-9]{9}$/'],
        ],[
            'contact_number.required' => 'Phone Number is required',
            'contact_number.numeric' => 'Phone Number must be a number',
            'contact_number.regex' => 'Phone Number must start with 09 and must have 11 digits',
        ]);
        DB::beginTransaction();
        EmployeeInformation::where('id', auth()->user()->id)
        ->update([
            'contact_number' => $this->contact_number,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Contact Number was added to your account.')->success()->send();
        return redirect()->route('requisitioner.dashboard');

    }
}
