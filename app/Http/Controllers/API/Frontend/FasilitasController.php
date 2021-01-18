<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function show($masjid_id, $id)
	{
		$data = Fasilitas::where('masjid_id', $masjid_id)
                ->where('id', $id)
                ->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Fasilitas::where('masjid_id', $masjid_id)->get();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $data = Fasilitas::paginate(5);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
