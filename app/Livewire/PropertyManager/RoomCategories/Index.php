<?php

namespace App\Livewire\PropertyManager\RoomCategories;

use App\Models\Rooms\RoomCategory;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class Index extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(RoomCategory::query())
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('rooms_count')
                    ->counts('rooms')
                    ->label('Rooms'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('edit')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->fillForm(fn (RoomCategory $record): array => [
                        'code' => $record->code,
                        'name' => $record->name,
                        'description' => $record->description,
                    ])
                    ->action(function (RoomCategory $record, array $data) {
                        $record->update($data);
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Create Room Category')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        RoomCategory::create($data);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.property-manager.room-categories.index');
    }
}
