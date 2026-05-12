<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\Signature;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateSignatureModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $uploadedSignature;
    public $activeTab = 'draw';

    protected $listeners = ['openSignatureModal' => 'openModal'];

    public function render()
    {
        return view('livewire.requisitioner.update-signature-modal');
    }

    public function openModal()
    {
        $this->reset('uploadedSignature');
        $this->resetErrorBag();
        $this->activeTab = 'draw';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('uploadedSignature');
        $this->resetErrorBag();
    }

    public function setActiveTab($tab)
    {
        if (in_array($tab, ['draw', 'upload', 'smart'])) {
            $this->activeTab = $tab;
            $this->resetErrorBag();
        }
    }

    public function saveSignature($data)
    {
        Signature::query()->updateOrCreate([
            'user_id' => auth()->id(),
        ], [
            'content' => $data,
        ]);

        Notification::make()
            ->title('Signature updated')
            ->body('Your signature has been replaced.')
            ->success()
            ->send();

        $this->closeModal();
        $this->dispatchBrowserEvent('signature-updated');
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

        Notification::make()
            ->title('Signature updated')
            ->body('Your signature has been replaced.')
            ->success()
            ->send();

        $this->closeModal();
        $this->dispatchBrowserEvent('signature-updated');
    }
}
