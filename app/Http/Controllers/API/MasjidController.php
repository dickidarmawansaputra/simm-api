<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasjidController extends Controller
{
	public function store()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $validator = Validator::make($json, [
         'nama_masjid' => 'required',
         'tipologi_masjid' => 'required',
         'deskripsi_masjid' => 'required',
         'alamat_masjid' => 'required',
         'kecamatan' => 'required',
         'kelurahan' => 'required',
         'gambar' => 'required',
         'tanggal_tahun_berdiri' => 'required',
         'status_tanah' => 'required',
         'luas_tanah' => 'required',
         'luas_bangunan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }
    	$data = Masjid::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Masjid::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('id', $json['masjid_id']);
                    }
                })->paginate(10);
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
        $validator = Validator::make($json, [
         'id' => 'required',
         'nama_masjid' => 'required',
         'tipologi_masjid' => 'required',
         'deskripsi_masjid' => 'required',
         'alamat_masjid' => 'required',
         'kecamatan' => 'required',
         'kelurahan' => 'required',
         'gambar' => 'required',
         'tanggal_tahun_berdiri' => 'required',
         'status_tanah' => 'required',
         'luas_tanah' => 'required',
         'luas_bangunan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }
        $data = Masjid::where('id', $json['id'])->update($json);
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Masjid::find($id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
