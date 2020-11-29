<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class MasjidController extends Controller
{
	public function store()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
    	$data = Masjid::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Masjid::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Masjid::where('id', $json['id'])->update($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function destroy($id)
    {
    	$data = Masjid::find($id)->delete();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
