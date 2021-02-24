<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class InventarisController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Inventaris::leftJoin('masjid', 'inventaris.masjid_id', 'masjid.id')
            ->where('inventaris.masjid_id', $masjid_id)
            ->where('inventaris.id', $id)
            ->where('inventaris.masjid_id', $masjid_id)
            ->select('inventaris.id', 'kode_inventaris', 'nama_inventaris', 'kondisi_inventaris', 'foto_inventaris', 'deskripsi_inventaris', 'masjid_id', 'masjid.nama_masjid')
            ->first();
        $data['foto_inventaris'] = URL::to('/').''.Storage::url($data['foto_inventaris']);
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Inventaris::leftJoin('masjid', 'inventaris.masjid_id', 'masjid.id')
            ->where('inventaris.masjid_id', $masjid_id)
            ->select('inventaris.id', 'kode_inventaris', 'nama_inventaris', 'kondisi_inventaris', 'foto_inventaris', 'deskripsi_inventaris', 'masjid_id', 'masjid.nama_masjid')
            ->get();
        foreach ($data as $key => $value) {
            $foto_inventaris = URL::to('/').''.Storage::url($value['foto_inventaris']);
            $value['foto_inventaris'] = $foto_inventaris;
        }
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAllLimit($masjid_id)
    {
        $data = Inventaris::leftJoin('masjid', 'inventaris.masjid_id', 'masjid.id')
            ->where('inventaris.masjid_id', $masjid_id)
            ->select('inventaris.id', 'kode_inventaris', 'nama_inventaris', 'kondisi_inventaris', 'foto_inventaris', 'deskripsi_inventaris', 'masjid_id', 'masjid.nama_masjid')
            ->limit(3)
            ->get();
        foreach ($data as $key => $value) {
            $foto_inventaris = URL::to('/').''.Storage::url($value['foto_inventaris']);
            $value['foto_inventaris'] = $foto_inventaris;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
