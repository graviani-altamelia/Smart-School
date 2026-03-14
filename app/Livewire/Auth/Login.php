<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            $role = Auth::user()->role;
            if ($role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'petugas') return redirect()->route('petugas.dashboard');
            
            return redirect()->route('siswa.dashboard');
        }

        $this->addError('email', 'Email atau password salah.');
        $this->dispatch('swal:error', message: 'Email atau password salah!');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}