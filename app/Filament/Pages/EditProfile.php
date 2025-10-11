<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\Action;
use App\Models\User;

class EditProfile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Mon profil';
    protected static ?string $title = 'Modifier mon profil';
    protected static ?string $slug = 'mon-profil';

    // protected static string $view = 'filament.pages.edit-profile';

    public $name;
    public $email;
    public $photo;
    public $password;
    public $password_confirmation;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->photo = $user->photo;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Nom complet')
                ->required(),

            TextInput::make('email')
                ->label('Adresse e-mail')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            FileUpload::make('photo')
                ->label('Photo de profil')
                ->image()
                ->directory('avatars')
                ->avatar()
                ->maxSize(1024),

            TextInput::make('password')
                ->label('Nouveau mot de passe')
                ->password()
                ->minLength(8)
                ->confirmed()
                ->nullable(),
                
            TextInput::make('password_confirmation')
                ->label('Confirmer le mot de passe')
                ->password()
                ->minLength(8)
                ->nullable(),
        ];
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'photo' => 'nullable|image|max:1024',
            'password' => 'nullable|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (isset($validated['photo'])) {
            $user->photo = $validated['photo'];
        }

        $user->save();

        Notification::make()
            ->title('Profil mis à jour avec succès')
            ->success()
            ->send();

        return redirect()->to($this->getUrl());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Sauvegarder')
                ->action('save')
                ->color('primary'),
        ];
    }

    protected function getFormModel(): User
    {
        return Auth::user();
    }
}