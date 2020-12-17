<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kepengurusan;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KepengurusanController extends Controller
{
    public function store(Request $request)
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $validator = Validator::make($json, [
         'nama' => 'required',
         'tempat_lahir' => 'required',
         'tanggal_lahir' => 'required',
         'jenis_kelamin' => 'required',
         'jabatan' => 'required',
         'periode' => 'required',
         'no_hp' => 'required',
         'email' => 'required',
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
    	$data = Kepengurusan::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Kepengurusan::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Kepengurusan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $validator = Validator::make($json, [
         'id' => 'required',
         'nama' => 'required',
         'tempat_lahir' => 'required',
         'tanggal_lahir' => 'required',
         'jenis_kelamin' => 'required',
         'jabatan' => 'required',
         'periode' => 'required',
         'no_hp' => 'required',
         'email' => 'required',
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
        $data = Kepengurusan::where('id', $json['id'])->update($json);
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Kepengurusan::find($id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
