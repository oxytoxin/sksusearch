<?php

namespace App\Filament\Resources\OfficeResource\RelationManagers;

use App\Models\EmployeeInformation;
use App\Models\Position;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeInformationRelationManager extends RelationManager
{
    protected static string $relationship = 'employee_information';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'description')
            ]);
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return 'Employees Under This Office';
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => route('filament.resources.employee-informations.edit', ['record' => $record]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->label('Name')->searchable(),
                TextColumn::make('position.description')->label('Position'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('assign')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Select::make('employee_information_id')
                                    ->label('Employee')
                                    ->options(EmployeeInformation::pluck('full_name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Select::make('position_id')
                                    ->options(Position::pluck('description', 'id'))
                                    ->label('Position')
                                    ->required()
                            ])
                    ])
                    ->action(function ($data, $livewire) {
                        $office = $livewire->getOwnerRecord();
                        EmployeeInformation::where('id', $data['employee_information_id'])
                            ->update([
                                'position_id' => $data['position_id'],
                                'office_id' => $office->id
                            ]);
                        Notification::make()
                            ->title('Employee Assigned.')
                            ->success()
                            ->send();
                    })
                    ->button()
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Unassign')
                    ->modalHeading('Unassign Employee')
                    ->modalSubheading('Are you sure you want to unassign this employee?')
                    ->action(function ($record) {
                        $record->update([
                            'position_id' => null,
                            'office_id' => null
                        ]);
                        Notification::make()
                            ->title('Employee Unassigned.')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([]);
    }
}
