<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Siswa extends Component
{
    public $name, $email, $password, $kelas, $jurusan, $siswa_id;

    public function render()
    {
        return view('livewire.admin.siswa', [
            'members' => User::where('role', 'siswa')->latest()->get()
        ])->layout('layouts.app');
    }

    private function resetInput()
    {
        $this->name = ''; $this->email = ''; $this->password = '';
        $this->kelas = ''; $this->jurusan = '';
    }

    public function store()
    {
        $this->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'kelas'    => 'required',
            'jurusan'  => 'required',
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'kelas'    => $this->kelas,
            'jurusan'  => $this->jurusan,
            'role'     => 'siswa',
        ]);

        session()->flash('message', 'Siswa berhasil ditambahkan!');
        $this->resetInput();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Data siswa dihapus!');
    }
}