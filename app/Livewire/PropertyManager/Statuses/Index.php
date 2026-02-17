<?php

namespace App\Livewire\PropertyManager\Statuses;

use App\Models\Rooms\Status;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
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
            ->query(Status::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
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
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->fillForm(fn (Status $record): array => [
                        'name' => $record->name,
                    ])
                    ->action(function (Status $record, array $data) {
                        $record->update($data);
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Create Status')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (array $data) {
                        Status::create($data);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.property-manager.statuses.index');
    }
}
