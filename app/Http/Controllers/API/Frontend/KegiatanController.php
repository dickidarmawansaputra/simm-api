<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function show($id)
	{
		$data = Kegiatan::find($id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Kegiatan::all();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
