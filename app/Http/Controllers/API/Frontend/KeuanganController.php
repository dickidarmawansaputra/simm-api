<?php

namespace App\Http\Controllers\API\Frontend;

use App\Exports\KeuanganExport;
use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\SaldoKeuangan;
use Illuminate\Http\Request;
use PDF;
use Excel;

class KeuanganController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Keuangan::leftJoin('masjid', 'keuangan.masjid_id', 'masjid.id')
            ->where('keuangan.masjid_id', $masjid_id)
            ->where('keuangan.id', $id)
            ->select('jenis_keuangan', 'sumber', 'jumlah', 'keterangan', 'tanggal', 'masjid_id', 'masjid.nama_masjid')
            ->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Keuangan::leftJoin('masjid', 'keuangan.masjid_id', 'masjid.id')
            ->where('keuangan.masjid_id', $masjid_id)
            ->select('jenis_keuangan', 'sumber', 'jumlah', 'keterangan', 'tanggal', 'masjid_id', 'masjid.nama_masjid')
            ->get();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAllLimit($masjid_id)
    {
        $data = Keuangan::leftJoin('masjid', 'keuangan.masjid_id', 'masjid.id')
            ->where('keuangan.masjid_id', $masjid_id)
            ->select('jenis_keuangan', 'sumber', 'jumlah', 'keterangan', 'tanggal', 'masjid_id', 'masjid.nama_masjid')
            ->limit(3)
            ->get();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function pdf($masjid_id)
    {
        $data = SaldoKeuangan::with('history')
            ->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
            ->where('saldo_keuangan.masjid_id', $masjid_id)
            ->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
            ->first();
        $pdf = PDF::loadView('laporan.keuanganpdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->stream('Laporan Keuangan.pdf');
    }

    public function excel($masjid_id)
    {
        return Excel::download(new KeuanganExport($masjid_id), 'Laporan Keuangan.xlsx');
    }
}
