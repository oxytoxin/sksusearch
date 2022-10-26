<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ViewArchives extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableFilters(): array
    {
        return [
            MultiSelectFilter::make('fund_cluster.name')
            ->label('Fund Cluster')
            ->relationship('fund_cluster', 'name')
            
        ];
    }
    protected function getTableQuery()
    {
        return DisbursementVoucher::where('current_step_id','>=','23000');
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number')
            ->searchable(),
            TextColumn::make('user.employee_information.full_name')
            ->searchable()
            ->label('Requisitioner'),            
            TextColumn::make('payee')
            ->searchable()
            ->label('Payee'),
            TextColumn::make('fund_cluster.name')
            ->searchable()
            ->label('Fund Cluster'),
            TextColumn::make('cheque_number')
            ->searchable()
            ->label('Cheque / ADA'),
            ViewColumn::make('disbursment_voucher_particulars.purpose')
            ->view('components.archiver.tables.columns.particulars-viewer-nlgc')
            ->label('Particular(s)'),
            TextColumn::make('disbursement_voucher_particulars_sum_amount')
            ->sum('disbursement_voucher_particulars', 'amount')
            ->label('Amount')
            ->money('php'),
        ];
    }
    protected function getTableActions(): array
    {
        return [
                Action::make('view_scanned_documents')
                ->url(fn (DisbursementVoucher $record): string => route('archiver.view-scanned-docs', [$record]))
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye')
                ->label('')
            ];
    }

    public function render()
    {
        return view('livewire.archiver.view-archives');
    }
}
