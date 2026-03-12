<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public bool $terms = false;

    public function register(): void
    {
        $this->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'name' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        session()->regenerate();
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
