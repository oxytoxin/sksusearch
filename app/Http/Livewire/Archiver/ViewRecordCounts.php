<?php

namespace App\Http\Livewire\Archiver;

use Livewire\Component;
use App\Models\LegacyDocument;

class ViewRecordCounts extends Component
{
    public $init_count;
    public $legacy_document_years;
    public $year;

    public function mount()
    {
        $this->init_count = LegacyDocument::count();
        $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
        $this->year = 'all';
    }

    public function updatedYear($value)
    {
        if($value == 'all'){
            $this->init_count = LegacyDocument::count();
            $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
        }else{
            $this->init_count = LegacyDocument::whereYear('journal_date', $value)->count();
            $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
        }
    }

    public function redirectBack()
    {
        return redirect()->route('archiver.view-archives');
    }
    public function render()
    {
        return view('livewire.archiver.view-record-counts');
    }
}
