<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function show($id)
	{
		$data = Inventaris::find($id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Inventaris::all();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
