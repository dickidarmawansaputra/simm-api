<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

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
            $data['foto_inventaris'] = (string) Image::make($request->foto_inventaris)->encode('data-url');
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
            $data['foto_inventaris'] = (string) Image::make($request->foto_inventaris)->encode('data-url');
        } else {
            unset($request->foto_inventaris);
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
    	$data = Inventaris::where('id', $id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }
}
