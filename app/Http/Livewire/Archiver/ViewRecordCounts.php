<?php

namespace App\Http\Livewire\Archiver;

use Livewire\Component;
use App\Models\LegacyDocument;

class ViewRecordCounts extends Component
{
    public $init_count;
    public $legacy_document_years;
    public $year;
    protected  $legacy_docs;

    public function mount()
    {
        $this->init_count = LegacyDocument::count();
        $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
        $this->year = 'all';
        $this->legacy_docs = LegacyDocument::paginate(25);
    }

    public function updatedYear($value)
    {
        if($value == 'all'){
            $this->init_count = LegacyDocument::count();
            $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
            $this->legacy_docs = LegacyDocument::paginate(25);
        }else{
            $this->init_count = LegacyDocument::whereYear('journal_date', $value)->count();
            $this->legacy_document_years = LegacyDocument::selectRaw('YEAR(journal_date) year')->distinct()->orderBy('year', 'desc')->get();
            $this->legacy_docs = LegacyDocument::whereYear('journal_date', $value)->paginate(25);
        }
    }

    public function redirectBack()
    {
        return redirect()->route('archiver.view-archives');
    }
    public function render()
    {
        return view('livewire.archiver.view-record-counts')->with('legacy_docs', $this->legacy_docs);
    }
}
