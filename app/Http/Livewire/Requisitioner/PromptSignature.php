<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\Signature;
use Filament\Notifications\Notification;
use Livewire\Component;

class PromptSignature extends Component
{
    public function render()
    {
        return view('livewire.requisitioner.prompt-signature');
    }

    public function saveSignature($data)
    {
        Signature::query()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'content' => $data
        ]);
        Notification::make()->title('Operation Success')->body('Signature was saved to your account.')->success()->send();
        return redirect()->route('requisitioner.dashboard');
    }
}
