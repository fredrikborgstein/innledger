<?php

namespace App\Livewire\PropertyManager\Rooms;

use App\Models\Rooms\Room;
use App\Models\Rooms\RoomCategory;
use App\Models\Rooms\Status;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
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
            ->query(Room::query())
            ->columns([
                TextColumn::make('room_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roomCategory.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('edit')
                    ->schema([
                        TextInput::make('room_number')
                            ->required()
                            ->maxLength(255),
                        Select::make('room_category_id')
                            ->label('Room Category')
                            ->options(RoomCategory::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('status_id')
                            ->label('Status')
                            ->options(Status::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->fillForm(fn (Room $record): array => [
                        'room_number' => $record->room_number,
                        'room_category_id' => $record->room_category_id,
                        'status_id' => $record->status_id,
                    ])
                    ->action(function (Room $record, array $data) {
                        $record->update($data);
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Create Room')
                    ->schema([
                        TextInput::make('room_number')
                            ->required()
                            ->maxLength(255),
                        Select::make('room_category_id')
                            ->label('Room Category')
                            ->options(RoomCategory::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('status_id')
                            ->label('Status')
                            ->options(Status::pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data) {
                        Room::create($data);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.property-manager.rooms.index');
    }
}
