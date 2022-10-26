<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use App\Models\LegacyDocument;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
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
            TextColumn::make('journal_date')
                ->label('Journal Date')
                ->date()
                ->searchable(),
            TextColumn::make('document_category')
                ->label('Journal Date')
                ->enum([
                    '1' => 'Disbursement Voucher',
                    '2' => 'Liquidation Report',
                    '3' => 'Cancelled Cheque',
                    '4' => 'Staled Cheque',
                ])
                ->searchable(),

        ];
    }

    private function getTableActions()
    {
        return [
            ActionGroup::make([
                ViewAction::make('legacy_document_details')
                    ->label('View Details')
                    ->icon('ri-list-check-2'),
                ViewAction::make('legacy_document_preview')
                    ->label('View Scanned Documents')
                    ->url(fn (LegacyDocument $record): string => route('archiver.view-scanned-docs-lgc', [$record]))
                    ->icon('ri-file-copy-2-line'),
                ViewAction::make('legacy_document_generate_qr')
                    ->label('Generate QR')
                    ->modalHeading('QR CODE')
                    ->modalContent(fn ($record) => view('components.archiver.tables.columns.legacy-document-qr', [
                        'legacy_document' => $record,
                    ]))
                    ->modalWidth('xs')
                    ->icon('ri-qr-code-line'),
                EditAction::make('legacy_document_edit')
                    ->label('Edit')
                    ->icon('ri-edit-2-line'),
            ])->icon('ri-flashlight-fill'),
            
        ];
    }
    public function render()
    {
        return view('livewire.archiver.view-legacy-documents');
    }
}
