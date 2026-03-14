<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\LogAktivitas as LogModel;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class Siswa extends Component
{
    use WithPagination;
    public $name, $email, $password, $kelas, $jurusan, $siswa_id;
    public $search = '';
    public $isEdit = false;

    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'kelas' => 'required',
            'jurusan' => 'required',
        ]);

        User::create([
            'name' => $this->name, 'email' => $this->email, 'password' => Hash::make($this->password),
            'kelas' => $this->kelas, 'jurusan' => $this->jurusan, 'role' => 'siswa',
        ]);

        LogModel::add('Tambah', 'User', 'Mendaftarkan siswa baru: ' . $this->name);

        session()->flash('message', 'Siswa berhasil ditambahkan! ✅');
        $this->resetInput();
    }

    public function update()
    {
        $user = User::find($this->siswa_id);
        $user->update([
            'name' => $this->name, 'email' => $this->email,
            'kelas' => $this->kelas, 'jurusan' => $this->jurusan,
        ]);

        LogModel::add('Edit', 'User', 'Mengubah data siswa: ' . $this->name);

        session()->flash('message', 'Data siswa diperbarui! ✨');
        $this->resetInput();
    }

    public function delete($id)
    {
        $user = User::find($id);
        if($user){
            $nama = $user->name;
            $user->delete();
            LogModel::add('Hapus', 'User', 'Menghapus data siswa: ' . $nama);
        }
        session()->flash('message', 'Data siswa dihapus! 🗑️');
    }

    public function render()
    {
        return view('livewire.admin.siswa', [
            'members' => User::where('role', 'siswa')->where('name', 'like', '%' . $this->search . '%')->latest()->paginate(10)
        ])->layout('layouts.app');
    }
    public function resetInput() { $this->reset(['name', 'email', 'password', 'kelas', 'jurusan', 'siswa_id', 'isEdit']); }
    public function edit($id) {
        $user = User::findOrFail($id);
        $this->siswa_id = $id; $this->name = $user->name; $this->email = $user->email;
        $this->kelas = $user->kelas; $this->jurusan = $user->jurusan; $this->isEdit = true;
    }
}