<?php

namespace App\Exports;

use App\Models\WfpDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class GenerateWfpPpmpExport   implements FromView
{
    public function __construct(public $record,public $total)
    {}
    public function view(): View
    {

        return view('generate-wfp-ppmp-report',[
            'record' => $this->record,
            'total' => $this->total
        ]);
    }
}
