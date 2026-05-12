<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\Signature;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class PromptSignature extends Component
{
    use WithFileUploads;

    public $uploadedSignature;
    public $activeTab = 'draw';

    public function setActiveTab($tab)
    {
        if (in_array($tab, ['draw', 'upload'])) {
            $this->activeTab = $tab;
            $this->resetErrorBag();
        }
    }

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

    public function saveUploadedSignature()
    {
        $this->validate([
            'uploadedSignature' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'uploadedSignature.required' => 'Please choose an image file first.',
            'uploadedSignature.image'    => 'The file must be an image.',
            'uploadedSignature.mimes'    => 'Only PNG or JPG images are allowed.',
            'uploadedSignature.max'      => 'Image size must not exceed 2MB.',
        ]);

        $file = $this->uploadedSignature;
        $base64 = 'data:' . $file->getMimeType() . ';base64,'
            . base64_encode(file_get_contents($file->getRealPath()));

        Signature::query()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'content' => $base64,
        ]);

        Notification::make()->title('Operation Success')->body('Signature was saved to your account.')->success()->send();
        return redirect()->route('requisitioner.dashboard');
    }
}
