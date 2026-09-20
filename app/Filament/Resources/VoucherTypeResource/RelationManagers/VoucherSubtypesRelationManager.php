<?php

namespace App\Filament\Resources\VoucherTypeResource\RelationManagers;

use App\Forms\Components\ArrayField;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherSubtypesRelationManager extends RelationManager
{
    protected static string $relationship = 'voucher_subtypes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add Voucher Subtype')
                    ->label('Add Voucher Subtype')
                    ->using(function (RelationManager $livewire, array $data): Model {
                        $documents = [];
                        if (isset($data['documents'])) {
                            $documents = $data['documents'];
                            unset($data['documents']);
                        }
                        $record = $livewire->getRelationship()->create($data);
                        $record->related_documents_list()->create([
                            'documents' => $documents
                        ]);
                        return $record;
                    })
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(191),
                        ArrayField::make('documents')
                            ->placeholder('Add document (Press Enter)')
                            ->label('Required Documents')
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, $record) {
                        $data['documents'] = $record->related_documents_list?->documents ?? [];
                        return $data;
                    })
                    ->action(function ($data, $record) {
                        $documents = [];
                        if (isset($data['documents'])) {
                            $documents = $data['documents'];
                            unset($data['documents']);
                        }
                        $record->update($data);
                        $record->related_documents_list()->updateOrCreate([
                            'voucher_sub_type_id' => $record->id
                        ], [
                            'documents' => $documents
                        ]);
                        Notification::make()->title('Saved.')->success()->send();
                    })
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(191),
                        ArrayField::make('documents')
                            ->placeholder('Add document (Press Enter)')
                            ->label('Required Documents')
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('order_column')
            ->defaultSort('order_column');
    }
}
