<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use DataTables;
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
            $fileName = $request->foto_fasilitas->getClientOriginalName();
            $path = $request->file('foto_fasilitas')->storeAs('public/fasilitas', $fileName);
            $data['foto_fasilitas'] = $path;
        }

        $result = Fasilitas::create($data);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $model = Fasilitas::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                });
        return Datatables::of($model)
            ->addColumn('foto_fasilitas', function($model) {
                return URL::to('/').''.Storage::url($model['foto_fasilitas']);
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
            $fileName = $request->foto_fasilitas->getClientOriginalName();
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
        Storage::delete($data->foto_fasilitas);
        $data->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
