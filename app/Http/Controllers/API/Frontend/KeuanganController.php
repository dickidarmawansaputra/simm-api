<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function show($id)
	{
		$data = Keuangan::where('id', $id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Keuangan::all();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
