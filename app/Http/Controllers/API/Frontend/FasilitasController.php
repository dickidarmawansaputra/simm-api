<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function show($id)
	{
		$data = Fasilitas::find($id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Fasilitas::all();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
