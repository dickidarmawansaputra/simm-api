<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use DataTables;
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

    public function data()
    {
        $model = Masjid::all();
        return Datatables::of($model)
            ->addColumn('gambar', function($model) {
                return URL::to('/').''.Storage::url($model['gambar']);
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
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
