<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $isRegister = false;
    
    // Properti form
    public $name, $email, $password, $kelas, $jurusan;
    public $role = 'siswa'; // Default role pendaftar baru

    public function toggleRegister()
    {
        $this->isRegister = !$this->isRegister;
        // Reset semua field agar bersih total saat pindah form
        $this->reset(['name', 'email', 'password', 'kelas', 'jurusan']);
        $this->role = 'siswa'; 
        $this->resetErrorBag();
    }

    public function register()
    {
        // Validasi diperketat
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'kelas'    => 'required|in:10,11,12',
            'jurusan'  => 'required|in:TO,TPFL,PPLG,ANIMASI,BCF',
        ];

        $this->validate($rules);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role'     => 'siswa', // Paksa pendaftar web jadi siswa
            'kelas'    => $this->kelas,
            'jurusan'  => $this->jurusan,
        ]);

        Auth::login($user);
        return redirect()->to('/');
    }

    public function login()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            // Logika Redirect Cerdas
            if (Auth::user()->role === 'admin' || Auth::user()->role === 'petugas') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->to('/');
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}