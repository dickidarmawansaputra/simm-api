<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\SaldoKeuangan;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
         'jenis_keuangan' => 'required',
         'sumber' => 'required',
         'jumlah' => 'required',
         'keterangan' => 'required',
         'tanggal' => 'required',
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
    	$result = Keuangan::create($data);
        $cek = SaldoKeuangan::where('masjid_id', $data['masjid_id'])->first();
        if ($cek) {
            if ($data['jenis_keuangan'] == 'masuk') {
                $saldo = SaldoKeuangan::where('masjid_id', $data['masjid_id'])->increment('saldo', $data['jumlah']);
            } else {
                $saldo = SaldoKeuangan::where('masjid_id', $data['masjid_id'])->decrement('saldo', $data['jumlah']);
            }
        } else {
            $saldo = SaldoKeuangan::create(['masjid_id' => $data['masjid_id'], 'saldo' => $data['jumlah']]);
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data(Request $request)
    {
        $model = Keuangan::where(function($query) use ($request) {
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
    	$data = Keuangan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
    
    public function update(Request $request)
    {
        $data = $request->all();
        $validator = Validsator::make($data, [
         'id' => 'required',
         'jenis_keuangan' => 'required',
         'sumber' => 'required',
         'jumlah' => 'required',
         'keterangan' => 'required',
         'tanggal' => 'required',
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
        $keuangan = Keuangan::where('id', $request->id)->first();
        if ($keuangan['jenis_keuangan'] == 'masuk') {
            if ($keuangan['jumlah'] < $request->jumlah) {
                $saldo = SaldoKeuangan::where('masjid_id', $keuangan['masjid_id'])->decrement('saldo', $keuangan['jumlah']);
            }
        }

        $result = Keuangan::where('id', $request->id)->update($data);
        if ($result == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::where('id', $id)->first();
        if ($keuangan['jenis_keuangan'] == 'masuk') {
            $saldo = SaldoKeuangan::where('masjid_id', $keuangan['masjid_id'])->decrement('saldo', $keuangan['jumlah']);
        } else {
            $saldo = SaldoKeuangan::where('masjid_id', $keuangan['masjid_id'])->increment('saldo', $keuangan['jumlah']);
        }
    	$data = Keuangan::find($id)->delete();
        if ($data == 1) {
            return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
        } else {
            return response()->json(['status' => 400, 'message' => 'success', 'data' => false]);
        }
    }
}
