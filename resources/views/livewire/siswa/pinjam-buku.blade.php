<div> 
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h3 class="fw-bold mb-3">Form Peminjaman Buku</h3>
                    <p class="text-muted">Halo <strong>{{ auth()->user()->name }}</strong>, silakan pilih buku yang ingin dipinjam.</p>

                    @if (session()->has('message'))
                        <div class="alert alert-success border-0 rounded-3 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="pinjam">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Judul Buku</label>
                            <select class="form-select rounded-3 shadow-sm" wire:model="buku_id">
                                <option value="">-- Pilih Buku yang Tersedia --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->judul }} (Stok: {{ $book->jumlah }})</option>
                                @endforeach
                            </select>
                            @error('buku_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Pinjam</label>
                                <input type="date" class="form-control rounded-3 shadow-sm bg-light" wire:model="tgl_pinjam" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Batas Kembali</label>
                                <input type="date" class="form-control rounded-3 shadow-sm" wire:model="tgl_kembali">
                                @error('tgl_kembali') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 mt-3 fw-bold shadow">
                            <i class="bi bi-send-fill me-2"></i> Ajukan Peminjaman
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>