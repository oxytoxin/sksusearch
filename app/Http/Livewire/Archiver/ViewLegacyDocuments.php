<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use App\Models\LegacyDocument;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class ViewLegacyDocuments extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return LegacyDocument::query();
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('payee_name')->label('Payee')->searchable(),
            TextColumn::make('document_code')->label('Document Code')
            ->searchable(),
            TextColumn::make('dv_number')->label('DV Number')
            ->searchable(),
            TextColumn::make('fund_cluster.name')->label('Fund Cluster')
            ->searchable(),
            ViewColumn::make('particulars')
            ->view('components.archiver.tables.columns.particulars-viewer')
            ->label('Particular(s)')
            ->searchable(),
            TextColumn::make('journal_date')->label('Journal Date')
            ->searchable(),
        ];
    }
    public function render()
    {
        return view('livewire.archiver.view-legacy-documents');
    }
}