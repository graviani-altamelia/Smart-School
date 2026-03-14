<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name, $email, $kelas, $jurusan, $password;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'kelas'    => 'required',
        'jurusan'  => 'required',
        'password' => 'required|min:6',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'kelas'    => $this->kelas,
            'jurusan'  => $this->jurusan,
            'password' => Hash::make($this->password),
            'role'     => 'siswa',
        ]);

        Auth::login($user);
        return redirect()->route('siswa.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}