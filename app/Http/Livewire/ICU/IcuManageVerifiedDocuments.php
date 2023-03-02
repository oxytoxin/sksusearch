<?php

namespace App\Http\Livewire\ICU;

use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class IcuManageVerifiedDocuments extends Component implements HasForms
{
    use InteractsWithForms;

    public DisbursementVoucher $disbursement_voucher;
    public $log_number;
    public $documents_verified_at;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('log_number')->required(),
            Flatpickr::make('documents_verified_at')->required()->label('Date'),
        ];
    }

    public function save()
    {
        $this->disbursement_voucher->update([
            'log_number' => $this->log_number,
            'documents_verified_at' => $this->documents_verified_at,
        ]);
        Notification::make()->title('Saved!')->success()->send();
    }

    public function mount()
    {
        $this->form->fill([
            'log_number' => $this->disbursement_voucher->log_number,
            'documents_verified_at' => $this->disbursement_voucher->documents_verified_at ?? now(),
        ]);
    }

    public function render()
    {
        return view('livewire.icu.icu-manage-verified-documents');
    }
}
