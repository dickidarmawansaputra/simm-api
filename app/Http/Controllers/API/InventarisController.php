<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class InventarisController extends Controller
{
    public function dashboard(Request $request)
    {
        $baik = Inventaris::where('masjid_id', $request->masjid_id)
            ->where('kondisi_inventaris', 'Baik')
            ->count();
        $sedang = Inventaris::where('masjid_id', $request->masjid_id)
            ->where('kondisi_inventaris', 'Sedang')
            ->count();
        $buruk = Inventaris::where('masjid_id', $request->masjid_id)
            ->where('kondisi_inventaris', 'Buruk')
            ->count();
        return response()->json(['status' => 200, 'message' => 'success', 'baik' => $baik, 'sedang' => $sedang, 'buruk' => $buruk]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'kode_inventaris' => 'required|unique:inventaris',
         'nama_inventaris' => 'required',
         'kondisi_inventaris' => 'required',
         'foto_inventaris' => 'required',
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

        if ($request->hasFile('foto_inventaris')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_inventaris->getClientOriginalName()));
            $path = $request->file('foto_inventaris')->storeAs('public/inventaris', $fileName);
            $data['foto_inventaris'] = $path;
        }

    	$result = Inventaris::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $data = Inventaris::where('masjid_id', $request->masjid_id)
        ->where(function ($query) use ($request) {
            if ($request->nama_inventaris) {
                $query->where('nama_inventaris', 'LIKE', '%'.$request->nama_inventaris.'%');
            }
        })
        ->where(function ($query) use ($request) {
            if ($request->kondisi_inventaris) {
                $query->where('kondisi_inventaris', $request->kondisi_inventaris);
            }
        })
        ->get();
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['foto_inventaris'] = URL::to('/').''.Storage::url($value->foto_inventaris);
            }
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
    }

    public function show($id)
    {
    	$data = Inventaris::where('id', $id)->first();
        $data['foto_inventaris'] = URL::to('/').''.Storage::url($data->foto_inventaris);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
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

        if ($request->hasFile('foto_inventaris')) {
            $fileName = str_replace(' ', '-', strtolower($request->foto_inventaris->getClientOriginalName()));
            $path = $request->file('foto_inventaris')->storeAs('public/inventaris', $fileName);
            $data['foto_inventaris'] = $path;
        } else {
            unset($data['foto_inventaris']);
        }
        
        $result = Inventaris::find($request->id)->update($data);
        if ($result == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }

    public function destroy($id)
    {
    	$data = Inventaris::find($id);
        Storage::delete($data->foto_inventaris);
        $data->delete();
        if ($data) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }
}
