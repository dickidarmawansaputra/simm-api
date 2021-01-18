<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function show($id)
	{
		$data = Inventaris::where('id', $id)->where('masjid_id', $masjid_id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll($masjid_id)
    {
    	$data = Inventaris::->where('masjid_id', $masjid_id)->get();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
