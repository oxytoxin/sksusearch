<?php

namespace App\Filament\Resources\OfficeResource\RelationManagers;

use App\Filament\Resources\EmployeeInformationResource;
use App\Models\EmployeeInformation;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeInformationRelationManager extends RelationManager
{
    protected static string $relationship = 'employee_information';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'description')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Employees Under This Office')
            ->recordTitleAttribute('full_name')
            ->recordUrl(fn (Model $record): string => EmployeeInformationResource::getUrl('edit', ['record' => $record]))
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
                    ->modalDescription('Are you sure you want to unassign this employee?')
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
