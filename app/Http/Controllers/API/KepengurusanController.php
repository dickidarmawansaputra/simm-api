<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KepengurusanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'nama' => 'required',
         'tempat_lahir' => 'required',
         'tanggal_lahir' => 'required',
         'jenis_kelamin' => 'required',
         'jabatan' => 'required',
         'periode' => 'required',
         'no_hp' => 'required',
         'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }
    	$result = Kepengurusan::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Kepengurusan::where('masjid_id', $request->masjid_id)->get();
        if (count($data) > 0) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
    }

    public function show($id)
    {
    	$data = Kepengurusan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
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
        $result = Kepengurusan::find($request->id)->update($data);
        if ($result == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request', 'data' => false]);
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
