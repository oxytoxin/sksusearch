<?php

namespace App\Http\Livewire\WFP;

use Carbon\Carbon;
use App\Models\Wfp;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Concerns\InteractsWithTable;

class WFPHistory extends Component implements HasTable
{
    use InteractsWithTable;
    public $cost_centers;

    public function mount()
    {
        $this->cost_centers = Auth::user()->employee_information->office->cost_centers()
            ->with('wpfPersonnel', function ($query) {
                $query->where('user_id', Auth::user()->id)
                ->orWhere('head_id', Auth::user()->id);
            })->get();
    }

    protected function getTableQuery()
    {
        return Wfp::query()->whereIn('cost_center_id', $this->cost_centers->pluck('id')->toArray())
        ->orWhereHas('costCenter.wpfPersonnel', function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhere('head_id', Auth::user()->id);
        });
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('wfpType.description')->label('WFP Type')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.name')->label('Cost Center')->searchable(),
            Tables\Columns\TextColumn::make('fundClusterWfp.name')->label('Fund Cluster')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.mfo.name')->label('MFO')->searchable(),
            Tables\Columns\TextColumn::make('fund_description')->searchable(),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Created')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('is_approved')
            ->label('Status')
            ->formatStateUsing(function ($record) {
                if($record->is_approved === 0)
                {
                    return 'Pending';
                }elseif($record->is_approved === 1)
                {
                    return 'Approved';
                }elseif($record->is_approved === 500){
                    return 'For Modification';
                }
            })->searchable(),
        ];
    }

    public function getTableActions()
    {
        return [
            Tables\Actions\ActionGroup::make([
                Action::make('view wfp')
                ->label('View WFP')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-wfp', $record))
                ->visible(fn ($record) => $record->is_approved === 0 || $record->is_approved === 1),
                Action::make('view ppmp')
                ->label('View PPMP')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-ppmp', $record))
                ->visible(fn ($record) => $record->is_approved === 0 || $record->is_approved === 1),
                Action::make('view pre')
                ->label('View PRE')
                ->button()
                ->icon('heroicon-o-eye')
                ->url(fn ($record): string => route('wfp.print-pre', $record))
                ->visible(fn ($record) => $record->is_approved === 0 || $record->is_approved === 1),
            ]),
            Action::make('continue_draft')
            ->label('Modify')
            ->color('warning')
            ->button()
            ->icon('heroicon-o-pencil')
            ->url(fn ($record): string => route('wfp.create-wfp', ['record' => $record->cost_center_id, 'wfpType' => $record->wpf_type_id, 'isEdit' => 1]))
            ->visible(fn ($record) => $record->is_approved === 500),
            Viewaction::make('view_remarks')
            ->label('View Remarks')
            ->color('success')
            ->button()
            ->icon('heroicon-o-eye')
            ->modalHeading('Remarks')
            ->modalContent(fn ($record) => view('components.wfp.remarks', [
                'record' => $record,
            ]))
            ->visible(fn ($record) => $record->is_approved === 500),
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.w-f-p-history');
    }
}
