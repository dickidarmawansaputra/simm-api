<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;

class MasjidController extends Controller
{
	public function show($id)
	{
		$data = Masjid::find($id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Masjid::all();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
