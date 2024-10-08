<?php

namespace App\Http\Livewire\WFP;

use Carbon\Carbon;
use App\Models\WfpRequestedSupply;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Auth;

class SupplyRequestList extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return WfpRequestedSupply::query()->where('user_id', Auth::id());
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('particulars')->label('Particular')->searchable(),
            Tables\Columns\TextColumn::make('unit_cost')->label('Unit Cost')->searchable(),
            Tables\Columns\TextColumn::make('status')->searchable(),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Requested')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
        ];
    }


    public function render()
    {
        return view('livewire.w-f-p.supply-request-list');
    }
}
