<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    public function store()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
        $validator = Validator::make($json, [
         'nama_kegiatan' => 'required',
         'deskripsi_kegiatan' => 'required',
         'jenis_kegiatan' => 'required',
         'foto_kegiatan' => 'required',
         'tanggal_waktu_kegiatan' => 'required',
         'pemateri' => 'required',
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
	  	$data = Kegiatan::create($json);
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Kegiatan::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
        $data = Kegiatan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
        $validator = Validator::make($json, [
         'id' => 'required',
         'nama_kegiatan' => 'required',
         'deskripsi_kegiatan' => 'required',
         'jenis_kegiatan' => 'required',
         'tanggal_waktu_kegiatan' => 'required',
         'pemateri' => 'required',
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
	  	$data = Kegiatan::where('id', $json['id'])->update($json);
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }

    public function destroy($id)
    {
        $data = Kegiatan::where('id', $id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
