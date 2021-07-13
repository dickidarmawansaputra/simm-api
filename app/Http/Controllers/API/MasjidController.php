<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class MasjidController extends Controller
{
	public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
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

        if ($request->hasFile('gambar')) {
            $fileName = str_replace(' ', '-', strtolower($request->gambar->getClientOriginalName()));
            $path = $request->file('gambar')->storeAs('public/masjid', $fileName);
            $data['gambar'] = $path;
        }

    	$result = Masjid::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Masjid::where(function ($query) use ($request) {
            if ($request->kecamatan) {
                $query->where('kecamatan', $request->kecamatan);
            }
        })
        ->where(function ($query) use ($request) {
            if ($request->tipologi_masjid) {
                $query->where('tipologi_masjid', $request->tipologi_masjid);
            }
        })
        ->get();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['gambar'] = URL::to('/').''.Storage::url($value->gambar);
            }
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
    }

    public function show($id)
    {
    	$data = Masjid::where('id', $id)->first();
        $data['gambar'] = URL::to('/').''.Storage::url($data->gambar);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'id' => 'required',
         'nama_masjid' => 'required',
         'tipologi_masjid' => 'required',
         'deskripsi_masjid' => 'required',
         'alamat_masjid' => 'required',
         'kecamatan' => 'required',
         'kelurahan' => 'required',
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
        if ($request->hasFile('gambar')) {
            $fileName = str_replace(' ', '-', strtolower($request->gambar->getClientOriginalName()));
            $path = $request->file('gambar')->storeAs('public/masjid', $fileName);
            $data['gambar'] = $path;
        } else {
            unset($data['gambar']);
        }
        $result = Masjid::find($request->id)->update($data);
        if ($result == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Masjid::find($id);
        Storage::delete($data->gambar);
        $data->delete();
        if ($data) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
