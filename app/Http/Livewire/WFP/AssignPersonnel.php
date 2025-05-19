<?php

namespace App\Http\Livewire\WFP;

use App\Models\CostCenter;
use App\Models\EmployeeInformation;
use App\Models\FundClusterWFP;
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
    public $fund_cluster;
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return WpfPersonnel::query()->where('head_id', auth()->user()->id);
    }


    protected function getTableHeaderActions(): array
    {
        return [
             Action::make('assign_personnel')
             ->icon('heroicon-o-plus-circle')
             ->button()
             ->form([
                Select::make('fund_cluster_w_f_p_s_id')
                ->label('Fund Cluster')
                ->required()
                ->searchable()
                ->preload()
                ->reactive()
                ->options(fn () => FundClusterWFP::whereIn('id', [1,2,3,4,5,6,7])->pluck('name', 'id')),
                Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->reactive()
                    ->options(function ($get) {
                        if ($get('fund_cluster_w_f_p_s_id') === '3') {
                            return EmployeeInformation::where('position_id', 39)
                            ->whereNotIn('id', [auth()->user()->employee_information->id])
                            //->whereDoesntHave('user.wfp_personnel')
                            ->pluck('full_name', 'user_id');
                        }else{

                            return EmployeeInformation::whereNotIn('id', [auth()->user()->employee_information->id])
                            //->whereDoesntHave('user.wfp_personnel')
                            ->pluck('full_name', 'user_id');
                        }
                    }),
                    //  ->options(fn () => EmployeeInformation::where('campus_id', auth()->user()->employee_information->campus_id)
                    // ->whereNotIn('id', [auth()->user()->employee_information->id])
                    // ->whereDoesntHave('user.wfp_personnel')
                    // ->pluck('full_name', 'user_id')),
                    Select::make('cost_center_id')
                    ->label('Cost Center')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->options(fn ($get) => CostCenter::whereHas('fundAllocations', function ($query) {
                        $query->where('is_locked', 1)->where('is_supplemental', 1);
                    })->whereHas('office', function ($query) {
                        $query->where('id', auth()->user()->employee_information->office_id);
                    })->whereDoesntHave('wpfPersonnel')
                    ->where('fund_cluster_w_f_p_s_id', $get('fund_cluster_w_f_p_s_id'))->pluck('name', 'id'))
             ])
             ->action(function ($data) {
                foreach ($data['user_id'] as $user_id) {
                    WpfPersonnel::create([
                        'user_id' => $user_id,
                        'office_id' => auth()->user()->employee_information->office_id,
                        'head_id' => auth()->user()->id,
                        'cost_center_id' => $data['cost_center_id'],
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
            Tables\Columns\TextColumn::make('cost_center.fundClusterWFP.name')->label('Fund Cluster')->searchable(),
            Tables\Columns\TextColumn::make('cost_center.name')->label('Cost Center')
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
