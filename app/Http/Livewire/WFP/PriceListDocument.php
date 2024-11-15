<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\PriceListDocument as PriceListDocumentModel;

class PriceListDocument extends Component
{
    public $record;

    public function mount()
    {
        $this->record = PriceListDocumentModel::first();
    }

    public function render()
    {
        return view('livewire.w-f-p.price-list-document');
    }

    public function download()
    {
        return response()->download(storage_path('app\public\pricelist-attachments' . $this->record->document), $this->record->document_name);
    }
}
