<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function store()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Fasilitas::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Fasilitas::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Fasilitas::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Fasilitas::where('id', $json['id'])->update($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function destroy($id)
    {
    	$data = Fasilitas::where('id', $id)->delete();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
