<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class FasilitasController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'nama_fasilitas' => 'required',
         'deskripsi_fasilitas' => 'required',
         'foto_fasilitas' => 'required',
         'kondisi_fasilitas' => 'required',
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

        if ($request->hasFile('foto_fasilitas')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_fasilitas->getClientOriginalName()));
            $path = $request->file('foto_fasilitas')->storeAs('public/fasilitas', $fileName);
            $data['foto_fasilitas'] = $path;
        }

        $result = Fasilitas::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Fasilitas::where('masjid_id', $request->masjid_id)->get();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['foto_fasilitas'] = URL::to('/').''.Storage::url($value->foto_fasilitas);
            }
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
    }

    public function show($id)
    {
        $data = Fasilitas::where('id', $id)->first();
        $data['foto_fasilitas'] = URL::to('/').''.Storage::url($data->foto_fasilitas);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'id' => 'required',
         'nama_fasilitas' => 'required',
         'deskripsi_fasilitas' => 'required',
         'kondisi_fasilitas' => 'required',
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
        if ($request->hasFile('foto_fasilitas')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_fasilitas->getClientOriginalName()));
            $path = $request->file('foto_fasilitas')->storeAs('public/fasilitas', $fileName);
            $data['foto_fasilitas'] = $path;
        } else {
            unset($data['foto_fasilitas']);
        }

        $data = Fasilitas::find($request->id)->update($data);
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Fasilitas::find($id);
        Storage::delete($data['foto_fasilitas']);
        $data->delete();
        if ($data) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
