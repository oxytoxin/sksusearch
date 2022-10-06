<?php

namespace App\Http\Livewire\Oic;

use App\Forms\Components\Flatpickr;
use App\Models\EmployeeInformation;
use App\Models\OicUser;
use Carbon\Carbon;
use DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OicAssign extends Component implements HasTable
{

    use InteractsWithTable;

    public $oic_id;
    public $valid_from;
    public $valid_to;

    protected function getFormSchema()
    {
        return [
            Select::make('oic_id')
                ->label('OIC')
                ->placeholder('Search User')
                ->options(EmployeeInformation::pluck('full_name', 'user_id'))
                ->searchable()
                ->validationAttribute('OIC User')
                ->notIn([auth()->id()])
                ->required(),
            Grid::make(2)->schema([
                Flatpickr::make('valid_from')
                    ->label('Valid From')
                    ->placeholder('Select Start Date')
                    ->default(today())
                    ->disableTime()
                    ->required(),
                Flatpickr::make('valid_to')
                    ->label('Valid To')
                    ->placeholder('Select End Date')
                    ->disableTime(),
            ])
        ];
    }

    protected function getTableQuery()
    {
        return OicUser::where('user_id', auth()->id());
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('oic.employee_information.full_name')->label('Name')->searchable(),
            TextColumn::make('valid_from')->dateTime('F d, Y'),
            TextColumn::make('valid_to')->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('F d, Y') : 'Present';
            }),
        ];
    }

    public function getTableActions()
    {
        return [
            EditAction::make('edit')
                ->action(function ($data, $record) {
                    $record->update($data);
                    Notification::make()->title('Saved.')->success()->send();
                })
                ->form(function ($record) {
                    return [
                        Grid::make(2)->schema([
                            Flatpickr::make('valid_from')
                                ->label('Valid From')
                                ->placeholder('Select Start Date')
                                ->disableTime()
                                ->required(),
                            Flatpickr::make('valid_to')
                                ->label('Valid To')
                                ->placeholder('Select End Date')
                                ->disableTime(),
                        ])
                    ];
                }),
            DeleteAction::make(),
        ];
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.oic.oic-assign');
    }

    public function assign()
    {
        $this->form->validate();
        auth()->user()->officers_in_charge()->attach($this->oic_id, [
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
        ]);

        Notification::make()->title('OIC Assigned')->body('OIC has been assigned successfully.')->success()->send();
    }
}
