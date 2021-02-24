<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

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
}
