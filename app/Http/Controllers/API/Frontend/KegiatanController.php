<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Kegiatan::where('masjid_id', $masjid_id)->where('id', $id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Kegiatan::where('masjid_id', $masjid_id)->get();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $data = Kegiatan::paginate(5);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
