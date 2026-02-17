<?php

namespace App\Livewire\PropertyManager\Roles;

use App\Models\Role;
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

class Index extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms, InteractsWithTable, InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->query(Role::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
            ])
            ->recordActions([
                Action::make('edit')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->fillForm(fn (Role $record): array => [
                        'name' => $record->name,
                        'description' => $record->description,
                    ])
                    ->action(function (Role $record, array $data) {
                        $record->update($data);
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Create Role')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        Role::create($data);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.property-manager.roles.index');
    }
}
