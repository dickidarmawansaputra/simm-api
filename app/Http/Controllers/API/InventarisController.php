<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class InventarisController extends Controller
{
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
            $fileName = $request->foto_inventaris->getClientOriginalName();
            $path = $request->file('foto_inventaris')->storeAs('public/inventaris', $fileName);
            $data['foto_inventaris'] = $path;
        }

    	$result = Inventaris::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $model = Inventaris::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                });
        return Datatables::of($model)
            ->addColumn('foto_inventaris', function($model) {
                return URL::to('/').''.Storage::url($model['foto_inventaris']);
            })
            ->addColumn('aksi', function($model) {
                return '
                <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark" onclick="lihatData('.$model->id.')"><i class="fa fa-eye"></i></a>
                <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark" onclick="editData('.$model->id.')"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark" onclick="hapusData('.$model->id.')"><i class="fa fa-trash"></i></a>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['aksi'])
            ->make(true);
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
            $fileName = $request->foto_inventaris->getClientOriginalName();
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
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }
}
