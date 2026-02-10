<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Kategori as KategoriModel;

class Kategori extends Component
{
    public $nama, $deskripsi;

    public function render()
    {
        return view('livewire.admin.kategori', [
            'categories' => KategoriModel::latest()->get()
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|min:3',
            'deskripsi' => 'nullable'
        ]);

        KategoriModel::create([
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ]);

        $this->reset(['nama', 'deskripsi']);
        session()->flash('message', 'Kategori berhasil ditambahkan!');
    }

    public function delete($id)
    {
        KategoriModel::find($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus!');
    }
}