<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpustakaan</title>
    <link rel="icon" type="image/png" href="{{ public_path('assets/login.png') }}">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .kop-surat { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-surat p { margin: 2px 0; font-size: 12px; }
        
        .info-laporan { margin-bottom: 15px; }
        .info-laporan h3 { margin: 0; color: #444; }

        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; color: #333; font-weight: bold; text-transform: uppercase; border: 1px solid #999; padding: 8px; }
        td { border: 1px solid #ccc; padding: 7px; vertical-align: top; }
        
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .bg-warning { background-color: #fff3cd; color: #856404; }
        .bg-success { background-color: #d4edda; color: #155724; }

        .footer { margin-top: 30px; text-align: right; }
        .ttd { margin-top: 50px; display: inline-block; text-align: center; width: 200px; }
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
        <p>Dicetak oleh: {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
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
                <td>{{ $item->user->name ?? 'User Dihapus' }}</td>
                <td>{{ $item->buku->judul ?? $item->judul }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                <td align="center">{{ \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y') }}</td>
                <td align="center">
                    <span class="badge {{ ($item->status_peminjaman ?? $item->status) == 'dipinjam' ? 'bg-warning' : 'bg-success' }}">
                        {{ strtoupper($item->status_peminjaman ?? $item->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" align="center">Tidak ada data peminjaman untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tgl_cetak }}</p>
        <div class="ttd">
            <p>Petugas Perpustakaan,</p>
            <br><br><br>
            <strong>( {{ auth()->user()->name }} )</strong>
        </div>
    </div>
</body>
</html>