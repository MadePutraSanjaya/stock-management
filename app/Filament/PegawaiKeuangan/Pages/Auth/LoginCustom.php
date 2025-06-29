<?php

namespace App\Filament\PegawaiKeuangan\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Illuminate\Contracts\Support\Htmlable;

class LoginCustom extends Login
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('NIP'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nip' => $data['login'],
            'password' => $data['password'],
        ];
    }

     public function getHeading(): string | Htmlable
    {
        return new \Illuminate\Support\HtmlString('
            <div class="text-center mb-6">
                <img src="' . asset('images/logo.jpeg') . '" alt="Logo" class="h-16 w-auto mx-auto mb-4">
            </div>
        ');
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null; 
    }
}
