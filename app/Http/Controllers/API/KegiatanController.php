<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'nama_kegiatan' => 'required',
         'deskripsi_kegiatan' => 'required',
         'jenis_kegiatan' => 'required',
         'foto_kegiatan' => 'required',
         'tanggal_waktu_kegiatan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 409,
                'message' => $validator->errors(),
                'data' => false,
            ]);
        }

        if ($request->hasFile('foto_kegiatan')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_kegiatan->getClientOriginalName()));
            $path = $request->file('foto_kegiatan')->storeAs('public/kegiatan', $fileName);
            $data['foto_kegiatan'] = $path;
        }

        $data['tanggal_waktu_kegiatan'] = Carbon::parse($request->tanggal_waktu_kegiatan)->format('Y-m-d H:i:s');
	  	$result = Kegiatan::create($data);
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Kegiatan::where('masjid_id', $request->masjid_id)->get();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['foto_kegiatan'] = URL::to('/').''.Storage::url($value->foto_kegiatan);
            }
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
    }

    public function show($id)
    {
        $data = Kegiatan::where('id', $id)->first();
        $data['foto_kegiatan'] = URL::to('/').''.Storage::url($data->foto_kegiatan);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
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

        if ($request->hasFile('foto_kegiatan')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_kegiatan->getClientOriginalName()));
            $path = $request->file('foto_kegiatan')->storeAs('public/kegiatan', $fileName);
            $data['foto_kegiatan'] = $path;
        } else {
            unset($data['foto_kegiatan']);
        }
        
        $data['tanggal_waktu_kegiatan'] = Carbon::parse($request->tanggal_waktu_kegiatan)->format('Y-m-d H:i:s');

	  	$result = Kegiatan::find($request->id)->update($data);
        if ($result == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }

    public function destroy($id)
    {
        $data = Kegiatan::find($id);
        Storage::delete($data->foto_kegiatan);
        $data->delete();
        if ($data) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }
}
