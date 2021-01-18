<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;

class MasjidController extends Controller
{
	public function show($id)
	{
		$data = Masjid::where('id', $id)->first();
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Masjid::paginate(5);
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
