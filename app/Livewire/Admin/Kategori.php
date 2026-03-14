<?php

namespace App\Livewire\Admin;

use App\Models\Kategori as ModelKategori;
use Livewire\Component;

class Kategori extends Component
{
    // Menggunakan variabel yang sama dengan Blade kamu
    public $nama, $deskripsi;

    public function render()
    {
        return view('livewire.admin.kategori', [
            'categories' => ModelKategori::latest()->get()
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|min:3|unique:kategoris,nama_kategori',
        ]);

        ModelKategori::create([
            'nama_kategori' => $this->nama, // Simpan ke kolom nama_kategori
            'deskripsi' => $this->deskripsi
        ]);

        $this->reset(['nama', 'deskripsi']);
        session()->flash('message', 'Kategori berhasil ditambahkan! ✨');
    }

    public function delete($id)
    {
        ModelKategori::destroy($id);
        session()->flash('message', 'Kategori telah dihapus.');
    }
}