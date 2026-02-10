<?php

namespace App\Livewire\Admin;

use Livewire\Component; 
use App\Models\Buku as BukuModel;
use App\Models\Kategori;

class Buku extends Component 
{
    public $judul, $penulis, $penerbit, $tahun, $jumlah, $kategori_id;

    public function render()
    {
        return view('livewire.admin.buku', [
            'books' => BukuModel::with('kategori')->latest()->get(),
            'categories' => Kategori::all()
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'judul' => 'required',
            'kategori_id' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        BukuModel::create([
            'judul' => $this->judul,
            'kategori_id' => $this->kategori_id,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'jumlah' => $this->jumlah,
        ]);

        session()->flash('message', 'Buku berhasil ditambahkan!');
        $this->reset();
    }

    public function delete($id)
    {
        BukuModel::find($id)->delete();
        session()->flash('message', 'Buku berhasil dihapus!');
    }
}