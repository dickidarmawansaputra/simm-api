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
    public function dashboard(Request $request)
    {
        $kajian = Kegiatan::where('masjid_id', $request->masjid_id)
            ->where('jenis_kegiatan', 'Kajian')
            ->count();
        $sosial = Kegiatan::where('masjid_id', $request->masjid_id)
            ->where('jenis_kegiatan', 'Sosial')
            ->count();
        $lainnya = Kegiatan::where('masjid_id', $request->masjid_id)
            ->where('jenis_kegiatan', 'Lainnya')
            ->count();
        return response()->json(['status' => 200, 'message' => 'success', 'kajian' => $kajian, 'sosial' => $sosial, 'lainnya' => $lainnya]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'nama_kegiatan' => 'required',
         'deskripsi_kegiatan' => 'required',
         'jenis_kegiatan' => 'required',
         'foto_kegiatan' => 'required',
         'tanggal_kegiatan' => 'required',
         'waktu_kegiatan' => 'required',
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

        $tanggal = Carbon::parse($request->tanggal_kegiatan)->format('Y-m-d');
        $waktu = Carbon::parse($request->waktu_kegiatan)->format('H:i:s');

        $data['tanggal_waktu_kegiatan'] = $tanggal.' '.$waktu;
	  	$result = Kegiatan::create($data);
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Kegiatan::where('masjid_id', $request->masjid_id)
        ->where(function ($query) use ($request) {
            if ($request->jenis_kegiatan) {
                $query->where('jenis_kegiatan', $request->jenis_kegiatan);
            }
        })
        ->where(function ($query) use ($request) {
            if ($request->nama_kegiatan) {
                $query->where('nama_kegiatan', 'LIKE', '%'.$request->nama_kegiatan.'%');
            }
        })
        ->get();
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
         'tanggal_kegiatan' => 'required',
         'waktu_kegiatan' => 'required',
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

        $tanggal = Carbon::parse($request->tanggal_kegiatan)->format('Y-m-d');
        $waktu = Carbon::parse($request->waktu_kegiatan)->format('H:i:s');

        $data['tanggal_waktu_kegiatan'] = $tanggal.' '.$waktu;
        
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
