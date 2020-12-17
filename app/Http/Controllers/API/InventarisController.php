<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarisController extends Controller
{
    public function store()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $validator = Validator::make($json, [
         'kode_inventaris' => 'required|unique:inventaris',
         'nama_inventaris' => 'required',
         'kondisi_inventaris' => 'required',
         'deskripsi_inventaris' => 'required',
         'masjid_id' => 'required',
         'pengguna_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }
    	$data = Inventaris::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Inventaris::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Inventaris::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $validator = Validator::make($json, [
         'kode_inventaris' => 'required',
         'nama_inventaris' => 'required',
         'kondisi_inventaris' => 'required',
         'deskripsi_inventaris' => 'required',
         'masjid_id' => 'required',
         'pengguna_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }
        $data = Inventaris::where('id', $json['id'])->update($json);
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Inventaris::where('id', $id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
