<?php

namespace App\Http\Controllers\API;

use App\Models\Keuangan;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class KeuanganController extends Controller
{
    public function store()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
    	$data = Keuangan::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Keuangan::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Keuangan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
    
    public function update()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Keuangan::where('id', $json['id'])->update($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function destroy($id)
    {
    	$data = Keuangan::find($id)->delete();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
