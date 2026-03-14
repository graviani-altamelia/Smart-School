<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpustakaan - {{ $subjudul }}</title>
    {{-- Catatan: public_path digunakan untuk mengambil path file lokal untuk PDF engine --}}
    <link rel="icon" type="image/png" href="{{ public_path('assets/login.png') }}">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #2D1A12; line-height: 1.5; }
        
        /* Kop Surat Modern & Sinkron */
        .kop-surat { text-align: center; border-bottom: 3px double #C05621; padding-bottom: 10px; margin-bottom: 25px; }
        .kop-surat h2 { margin: 0; font-size: 20px; color: #2D1A12; text-transform: uppercase; letter-spacing: 2px; }
        .kop-surat p { margin: 2px 0; font-size: 11px; color: #666; }
        
        /* Info Laporan */
        .info-laporan { margin-bottom: 20px; }
        .info-laporan h3 { margin: 0; color: #C05621; text-transform: uppercase; font-size: 14px; }
        .info-laporan p { margin: 3px 0; color: #555; }

        /* Style Tabel Sinkron Admin */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #2D1A12; 
            color: #ffffff; 
            font-weight: bold; 
            text-transform: uppercase; 
            border: 1px solid #2D1A12; 
            padding: 10px 8px; 
            font-size: 10px;
        }
        td { border: 1px solid #e0e0e0; padding: 8px; vertical-align: middle; }
        tr:nth-child(even) { background-color: #fdf8f5; } /* Efek zebra warna soft earth */
        
        /* Badge Sinkron Admin/Siswa */
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 9px; font-weight: bold; display: inline-block; }
        .bg-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .bg-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Footer & TTD */
        .footer { margin-top: 40px; }
        .footer-content { float: right; width: 200px; text-align: center; }
        .tgl-cetak { font-size: 10px; color: #777; margin-bottom: 15px; text-align: right; }
        .ttd { margin-top: 10px; }
        .signature-space { height: 60px; }
        .name-tag { border-bottom: 1px solid #2D1A12; padding-bottom: 2px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>PERPUSTAKAAN SMART SCHOOL</h2>
        <p>Jl. Raya Laladon, Kec Ciomas, Kab Bogor - Indonesia</p>
        <p>Email: perpus@smartschool.sch.id | Telp: (021) 123456</p>
    </div>

    <div class="info-laporan">
        <h3>{{ $subjudul }}</h3>
        <p>Dicetak oleh: <strong>{{ auth()->user()->name }}</strong> | Jabatan: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Siswa</th>
                <th width="30%">Judul Buku</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Tgl Kembali</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $item)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->user->name ?? 'User Dihapus' }}</strong><br>
                    <small style="color: #777;">NIS: {{ $item->user->nis ?? '-' }}</small>
                </td>
                <td>{{ $item->buku->judul ?? ($item->judul ?? 'Buku Tidak Ditemukan') }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tgl_pinjam ?? $item->created_at)->format('d/m/Y') }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}</td>
                <td align="center">
                    <span class="badge {{ ($item->status_peminjaman ?? $item->status) == 'dipinjam' ? 'bg-warning' : 'bg-success' }}">
                        {{ strtoupper($item->status_peminjaman ?? $item->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center" style="padding: 30px; color: #999;">Tidak ada data peminjaman untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p class="tgl-cetak">Dicetak pada: {{ $tgl_cetak }}</p>
        <div class="footer-content">
            <p>Bogor, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Petugas Perpustakaan,</p>
            <div class="signature-space"></div>
            <p class="name-tag">{{ auth()->user()->name }}</p>
            <p style="font-size: 9px; margin-top: 5px;">NIP/ID: {{ auth()->user()->id }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>