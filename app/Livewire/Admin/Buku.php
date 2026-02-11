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
            'bukus' => BukuModel::with('kategori')->latest()->get(),
            'kategoris' => Kategori::all()
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'judul' => 'required|min:3',
            'kategori_id' => 'required|exists:kategoris,id', // Memastikan ID kategori benar-benar ada
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required|numeric|digits:4',
            'jumlah' => 'required|numeric|min:1',
        ], [
            'kategori_id.required' => 'Silakan pilih kategori terlebih dahulu.',
            'tahun.digits' => 'Tahun harus format 4 angka (Contoh: 2026).',
        ]);

        BukuModel::create([
            'judul' => $this->judul,
            'kategori_id' => $this->kategori_id,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'jumlah' => $this->jumlah,
        ]);

        session()->flash('message', 'Buku "' . $this->judul . '" berhasil ditambahkan!');

        $this->reset();
    }

    public function delete($id)
    {
        $buku = BukuModel::find($id);
        
        if ($buku) {
            $judulBuku = $buku->judul;
            $buku->delete();
            session()->flash('message', 'Buku "' . $judulBuku . '" berhasil dihapus!');
        }
    }
}