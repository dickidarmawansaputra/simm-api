<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class KegiatanController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Kegiatan::leftJoin('masjid', 'kegiatan.masjid_id', 'masjid.id')
            ->select('kegiatan.id', 'kegiatan.nama_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.jenis_kegiatan', 'kegiatan.foto_kegiatan', 'kegiatan.tanggal_waktu_kegiatan', 'masjid.nama_masjid', 'masjid.alamat_masjid', 'masjid_id')
            ->where('kegiatan.masjid_id', $masjid_id)
            ->where('kegiatan.id', $id)
            ->first();
        $data['foto_kegiatan'] = URL::to('/').''.Storage::url($data['foto_kegiatan']);
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Kegiatan::leftJoin('masjid', 'kegiatan.masjid_id', 'masjid.id')
            ->select('kegiatan.id', 'kegiatan.nama_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.jenis_kegiatan', 'kegiatan.foto_kegiatan', 'kegiatan.tanggal_waktu_kegiatan', 'masjid.nama_masjid', 'masjid.alamat_masjid', 'masjid_id')
            ->where('kegiatan.masjid_id', $masjid_id)
            ->orderBy('kegiatan.id', 'DESC')
            ->get();
        foreach ($data as $key => $value) {
            $foto_kegiatan = URL::to('/').''.Storage::url($value['foto_kegiatan']);
            $value['foto_kegiatan'] = $foto_kegiatan;
        }
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAllLimit($masjid_id)
    {
        $data = Kegiatan::leftJoin('masjid', 'kegiatan.masjid_id', 'masjid.id')
            ->select('kegiatan.id', 'kegiatan.nama_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.jenis_kegiatan', 'kegiatan.foto_kegiatan', 'kegiatan.tanggal_waktu_kegiatan', 'masjid.nama_masjid', 'masjid.alamat_masjid', 'masjid_id')
            ->where('kegiatan.masjid_id', $masjid_id)
            ->orderBy('kegiatan.id', 'DESC')
            ->limit(3)
            ->get();
        foreach ($data as $key => $value) {
            $foto_kegiatan = URL::to('/').''.Storage::url($value['foto_kegiatan']);
            $value['foto_kegiatan'] = $foto_kegiatan;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $data = Kegiatan::leftJoin('masjid', 'kegiatan.masjid_id', 'masjid.id')
            ->select('kegiatan.id', 'kegiatan.nama_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.jenis_kegiatan', 'kegiatan.foto_kegiatan', 'kegiatan.tanggal_waktu_kegiatan', 'masjid.nama_masjid', 'masjid.alamat_masjid', 'masjid_id')
            ->limit(3)
            ->orderBy('kegiatan.id', 'DESC')
            ->get();
        foreach ($data as $key => $value) {
            $foto_kegiatan = URL::to('/').''.Storage::url($value['foto_kegiatan']);
            $value['foto_kegiatan'] = $foto_kegiatan;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function dataAll()
    {
        $data = Kegiatan::leftJoin('masjid', 'kegiatan.masjid_id', 'masjid.id')
            ->select('kegiatan.id', 'kegiatan.nama_kegiatan', 'kegiatan.deskripsi_kegiatan', 'kegiatan.jenis_kegiatan', 'kegiatan.foto_kegiatan', 'kegiatan.tanggal_waktu_kegiatan', 'masjid.nama_masjid', 'masjid.alamat_masjid', 'masjid_id')
            ->orderBy('kegiatan.id', 'DESC')
            ->get();
        foreach ($data as $key => $value) {
            $foto_kegiatan = URL::to('/').''.Storage::url($value['foto_kegiatan']);
            $value['foto_kegiatan'] = $foto_kegiatan;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
