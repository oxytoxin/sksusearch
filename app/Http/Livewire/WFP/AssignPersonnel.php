<?php

namespace App\Http\Livewire\WFP;

use App\Models\CostCenter;
use App\Models\EmployeeInformation;
use App\Models\WpfPersonnel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;

class AssignPersonnel extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return WpfPersonnel::query()->where('office_id', auth()->user()->employee_information->office_id);
    }


    protected function getTableHeaderActions(): array
    {
        return [
             Action::make('assign_personnel')
             ->icon('heroicon-o-plus-circle')
             ->button()
             ->form([
                Select::make('user_id')
                    ->label('This is all the available users in this campus')
                    ->required()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => EmployeeInformation::where('campus_id', auth()->user()->employee_information->campus_id)
                    ->whereNotIn('id', [auth()->user()->employee_information->id])
                    ->whereDoesntHave('user.wfp_personnel')
                    ->pluck('full_name', 'user_id'))
             ])
             ->action(function ($data) {
                foreach ($data['user_id'] as $user_id) {
                    WpfPersonnel::create([
                        'user_id' => $user_id,
                        'office_id' => auth()->user()->employee_information->office_id,
                        'head_id' => auth()->user()->id
                    ]);
                }

                Notification::make()->title('Operation Success')->body('Users are allowed to create a WFP')->success()->send();
             })
        ];
    }


    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('user.employee_information.full_name')
            ->wrap()
            ->searchable(),
            Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable(),
            Tables\Columns\TextColumn::make('user.employee_information.position.description')
            ->wrap()
            ->searchable(),
        ];
    }

    protected function getTableActions()
    {
        return [
            DeleteAction::make('delete')
            ->label('Remove Access')
            ->button()
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->modalHeading('Remove Access')
            ->requiresConfirmation()
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.assign-personnel');
    }
}
