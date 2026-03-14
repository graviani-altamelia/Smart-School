<?php

namespace App\Livewire\Admin;

use App\Models\Buku as ModelBuku;
use App\Models\Kategori;
use App\Models\LogAktivitas as LogModel;
use Livewire\Component;
use Livewire\WithPagination;

class Buku extends Component
{
    use WithPagination;
    public $judul, $penulis, $penerbit, $tahun, $jumlah, $kategori_id, $buku_id;
    public $search = '';
    public $isEdit = false;

    public function simpan()
    {
        $this->validate([
            'kategori_id' => 'required',
            'judul' => 'required',
            'jumlah' => 'required|numeric',
        ]);

        ModelBuku::create([
            'kategori_id' => $this->kategori_id,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'jumlah' => $this->jumlah,
        ]);

        LogModel::add('Tambah', 'Buku', 'Menambah buku baru: ' . $this->judul);

        $this->resetInput();
        session()->flash('pesan', 'Buku berhasil disimpan! 📚');
    }

    public function update()
    {
        $buku = ModelBuku::find($this->buku_id);
        $buku->update([
            'kategori_id' => $this->kategori_id,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'jumlah' => $this->jumlah,
        ]);

        LogModel::add('Edit', 'Buku', 'Mengubah data buku: ' . $this->judul);

        $this->resetInput();
        session()->flash('pesan', 'Buku diperbarui! ✨');
    }

    public function hapus($id)
    {
        $buku = ModelBuku::find($id);
        if($buku){
            $judul = $buku->judul;
            $buku->delete();
            LogModel::add('Hapus', 'Buku', 'Menghapus buku: ' . $judul);
        }
        session()->flash('pesan', 'Buku dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.buku', [
            'semua_buku' => ModelBuku::with('kategori')->where('judul', 'like', '%' . $this->search . '%')->latest()->paginate(10),
            'semua_kategori' => Kategori::all()
        ])->layout('layouts.app');
    }

    public function resetInput() { $this->reset(['judul', 'penulis', 'penerbit', 'tahun', 'jumlah', 'kategori_id', 'isEdit', 'buku_id']); }
    public function edit($id) {
        $buku = ModelBuku::findOrFail($id);
        $this->buku_id = $id; $this->kategori_id = $buku->kategori_id; $this->judul = $buku->judul;
        $this->penulis = $buku->penulis; $this->penerbit = $buku->penerbit; $this->tahun = $buku->tahun;
        $this->jumlah = $buku->jumlah; $this->isEdit = true;
    }
}