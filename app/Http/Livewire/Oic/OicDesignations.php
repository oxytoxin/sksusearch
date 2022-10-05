<?php

namespace App\Http\Livewire\Oic;

use App\Models\OicUser;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class OicDesignations extends Component implements HasTable
{
    use InteractsWithTable;

    public function getTableQuery()
    {
        return OicUser::where('oic_id', auth()->id());
    }

    public function getTableColumns()
    {
        return [
            TextColumn::make('signatory.employee_information.full_name')->label('For')->searchable(),
            TextColumn::make('valid_from')->date()->sortable(),
            TextColumn::make('valid_to')->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('F d, Y') : 'Present';
            })->sortable(),
        ];
    }

    public function render()
    {
        return view('livewire.oic.oic-designations');
    }
}
