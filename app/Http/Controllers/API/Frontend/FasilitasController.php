<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FasilitasController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Fasilitas::leftJoin('masjid', 'fasilitas.masjid_id', 'masjid.id')
            ->select('fasilitas.id', 'nama_fasilitas', 'deskripsi_fasilitas', 'foto_fasilitas', 'kondisi_fasilitas', 'masjid_id', 'masjid.nama_masjid')
            ->where('fasilitas.masjid_id', $masjid_id)
            ->where('fasilitas.id', $id)
            ->first();
        $data['foto_fasilitas'] = URL::to('/').''.Storage::url($data['foto_fasilitas']);
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Fasilitas::leftJoin('masjid', 'fasilitas.masjid_id', 'masjid.id')
            ->select('fasilitas.id', 'nama_fasilitas', 'deskripsi_fasilitas', 'foto_fasilitas', 'kondisi_fasilitas', 'masjid_id', 'masjid.nama_masjid')
            ->where('fasilitas.masjid_id', $masjid_id)
            ->get();
        foreach ($data as $key => $value) {
            $foto_fasilitas = URL::to('/').''.Storage::url($value['foto_fasilitas']);
            $value['foto_fasilitas'] = $foto_fasilitas;
        }
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $data = Fasilitas::leftJoin('masjid', 'fasilitas.masjid_id', 'masjid.id')
            ->select('fasilitas.id', 'nama_fasilitas', 'deskripsi_fasilitas', 'foto_fasilitas', 'kondisi_fasilitas', 'masjid_id', 'masjid.nama_masjid')
            ->limit(3)
            ->get();
        foreach ($data as $key => $value) {
            $foto_fasilitas = URL::to('/').''.Storage::url($value['foto_fasilitas']);
            $value['foto_fasilitas'] = $foto_fasilitas;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function dataAll()
    {
        $data = Fasilitas::leftJoin('masjid', 'fasilitas.masjid_id', 'masjid.id')
            ->select('fasilitas.id', 'nama_fasilitas', 'deskripsi_fasilitas', 'foto_fasilitas', 'kondisi_fasilitas', 'masjid_id', 'masjid.nama_masjid')
            ->get();
        foreach ($data as $key => $value) {
            $foto_fasilitas = URL::to('/').''.Storage::url($value['foto_fasilitas']);
            $value['foto_fasilitas'] = $foto_fasilitas;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
