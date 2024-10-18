<?php

namespace App\Http\Livewire\WFP;

use App\Models\Wfp;
use Carbon\Carbon;
use App\Models\WfpRequestedSupply;
use WireUi\Traits\Actions;
use Filament\Tables\Actions\Action;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class SupplyRequestList extends Component implements HasTable
{
    use InteractsWithTable;
    use Actions;

    public $record;

    protected function getTableQuery()
    {
        return WfpRequestedSupply::query()->where('user_id', Auth::id());
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('particulars')->html()->wrap()->label('Particular')->searchable(),
            Tables\Columns\TextColumn::make('specification')->searchable(),
            Tables\Columns\TextColumn::make('unit_cost')
            ->formatStateUsing(fn ($record) => '₱ '.number_format($record->unit_cost, 2))
            ->label('Unit Cost')->searchable(),
            BadgeColumn::make('is_ppmp')
            ->label('PPMP')
            ->enum([
                1 => 'Yes',
                0 => 'No',
            ])
            ->colors([
                'success' => 1,
                'danger' => 0,
            ]),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Requested')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('status')->searchable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
                Action::make('edit')
                ->url(fn (WfpRequestedSupply $record): string => route('wfp.request-supply-edit', [$record]))
                ->button()
                ->color('warning')
                ->icon('heroicon-o-pencil')
                ->label('Edit')
                ->visible(fn ($record) => $record->status === 'Pending' || $record->status === 'Request Modification'),
                // Action::make('forward_supply')
                // ->label('Forward to Supply')
                // ->button()
                // ->icon('heroicon-o-arrow-right')
                // ->action(function ($record) {
                //     $this->dialog()->confirm([
                //         'title'       => 'Are you Sure?',
                //         'description' => 'Forward this request to supply?',
                //         'acceptLabel' => 'Yes, forward it',
                //         'method'      => 'forwardRequestSupply',
                //         'params'      => $record->id,
                //     ]);
                // })->visible(fn ($record) => $record->status === 'Pending'),
                Action::make('view')
                ->url(fn (WfpRequestedSupply $record): string => route('wfp.request-supply-view', [$record]))
                ->label('View Details')
                ->button()
                ->icon('heroicon-o-eye')
                ->color('primary')
            ];
    }

    public function forwardRequestSupply($record)
    {
       $this->record = WfpRequestedSupply::find($record);
       $this->record->update([
        'status' => 'Forwarded to Supply',
        'is_approved_supply' => 1,
        ]);

        Notification::make()->title('Operation Success')->body('Request has been forwarder to supply and to be validated')->success()->send();
    }

    public function forwardRequestAccounting($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->record->update([
            'status' => 'Forwarded to Accounting',
            'is_approved_finance' => 1,
        ]);
    }


    protected function getTableHeaderActions(): array
    {
        return [
             Action::make('new_request')
             ->icon('heroicon-o-plus-circle')
             ->button()
             ->url(fn (): string => route('wfp.request-supply'))
        ];
    }


    public function render()
    {
        return view('livewire.w-f-p.supply-request-list');
    }
}
