<?php

namespace App\Http\Controllers;

use App\Models\Pinjam;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function cetakPeminjaman(Request $request)
    {
        $filter = $request->query('filter', 'semua');
        $query = Pinjam::with(['user', 'buku']);

        $subjudul = "Semua Riwayat Peminjaman";

        if ($filter == 'mingguan') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
            $query->whereBetween('created_at', [$start, $end]);
            $subjudul = "Laporan Mingguan (" . $start->format('d/m') . " - " . $end->format('d/m/Y') . ")";
        } 
        elseif ($filter == 'bulanan') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            $subjudul = "Laporan Bulanan - " . Carbon::now()->format('F Y');
        }

        $data = $query->latest()->get();

        $pdf = Pdf::loadView('exports.peminjaman-pdf', [
            'peminjaman' => $data,
            'subjudul'   => $subjudul,
            'tgl_cetak'  => Carbon::now()->format('d/m/Y H:i')
        ]);

        return $pdf->stream('laporan-peminjaman-' . $filter . '.pdf');
    }
}