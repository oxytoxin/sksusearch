<?php

namespace App\Http\Livewire\Motorpool\Requests;

use App\Models\RequestSchedule;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Livewire\Component;

class RequestIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return RequestSchedule::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('purpose')
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_travel')
                ->label('Date of travel')
                ->date()
                ->sortable()
                ->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('edit')
                    ->icon('ri-edit-line'),
                ViewAction::make('print')
                    ->label('Print')
                    ->icon('ri-printer-fill')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record]), true),
            ])
        ];
    }

    public function render()
    {
        return view('livewire.motorpool.requests.request-index');
    }
}
