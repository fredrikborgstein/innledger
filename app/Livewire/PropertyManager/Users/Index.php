<?php

namespace App\Livewire\PropertyManager\Users;

use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions, InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role.name')
                    ->label('Role')
                    ->sortable(),
                IconColumn::make('property_manager')
                    ->boolean()
                    ->label('Property Manager'),
                IconColumn::make('password_change_required')
                    ->boolean()
                    ->label('Must Change Password'),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('role_id')
                            ->label('Role')
                            ->options(Role::pluck('name', 'id'))
                            ->nullable(),
                        Checkbox::make('property_manager')
                            ->label('Property Manager'),
                        Checkbox::make('password_change_required')
                            ->label('Require password change on next login'),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->label('New Password (leave blank to keep current)'),
                    ])
                    ->fillForm(fn (User $record): array => [
                        'name' => $record->name,
                        'email' => $record->email,
                        'role_id' => $record->role_id,
                        'property_manager' => $record->property_manager,
                        'password_change_required' => $record->password_change_required,
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update($data);
                    })
                    ->hidden(fn (User $record): bool => $record->id === auth()->id()),
                DeleteAction::make()
                    ->hidden(fn (User $record): bool => $record->id === auth()->id()),
            ])
            ->headerActions([
                Action::make('create')
                    ->label('Create User')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Select::make('role_id')
                            ->label('Role')
                            ->options(Role::pluck('name', 'id'))
                            ->nullable(),
                        Checkbox::make('property_manager')
                            ->label('Property Manager'),
                        Checkbox::make('password_change_required')
                            ->label('Require password change on first login')
                            ->default(true),
                        TextInput::make('password')
                            ->required()
                            ->default(fn () => Str::password(16))
                            ->copyable()
                            ->helperText('A random password has been generated. Click the copy icon to copy it.')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    ])
                    ->action(function (array $data) {
                        User::create($data);
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.property-manager.users.index');
    }
}
