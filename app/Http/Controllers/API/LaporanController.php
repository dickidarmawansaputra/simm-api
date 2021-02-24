<?php

namespace App\Http\Controllers\API;

use App\Exports\KeuanganExport;
use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\SaldoKeuangan;
use DataTables;
use Excel;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    public function data(Request $request)
    {
        $model = Keuangan::with('saldo')
                ->where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->jenis_keuangan) {
                        $query->where('jenis_keuangan', $request->jenis_keuangan);
                    }
                })
                ->get();
        return Datatables::of($model)
            ->addColumn('jenis_keuangan', function($model) {
                if ($model->jenis_keuangan == 'masuk') {
                    return '
                    <a href="#" class="chip chip-small bg-gray1-dark">
                        <i class="fa fa-sign-in-alt bg-green1-dark"></i>
                        <strong class="color-black font-400">Uang Masuk</strong>
                    </a>';
                } else {
                    return '
                    <a href="#" class="chip chip-small bg-gray1-dark">
                        <i class="fa fa-sign-out-alt bg-red1-dark"></i>
                        <strong class="color-black font-400">Uang Keluar</strong>
                    </a>';
                }
            })
            ->addColumn('jumlah', function($model) {
                return 'Rp. '.number_format($model->jumlah,0,',','.');
            })
            ->addColumn('saldo', function($model) {
                return 'Rp. '.number_format($model->saldo['saldo'],0,',','.');
            })
            ->addColumn('aksi', function($model) use ($request) {
                return '
                <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"><i class="fa fa-file-excel"></i></a>
                <a href="https://simmapi.zethlabs.id/api/v1/laporan/keuangan/pdf/'.$request->masjid_id.'" target="_blank" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark"><i class="fa fa-file-pdf"></i></a>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['jenis_keuangan', 'aksi', 'jumlah', 'saldo'])
            ->make(true);
    }

    public function datas(Request $request)
    {
        return$model = Keuangan::with('saldo')->get();
        $model = SaldoKeuangan::with('history')->get();
                // ->where(function($query) use ($request) {
                //     if ($request->level == 'operator') {
                //         $query->where('masjid_id', $request->masjid_id);
                //     }
                // });
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

    public function pdfKeuangan($masjid_id, $id)
    {
        return $data = Keuangan::where('masjid_id', $masjid_id)->where('id', $id)->first();
    	return$data = SaldoKeuangan::with('history')
    		->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
    		->where('saldo_keuangan.masjid_id', $masjid_id)
    		->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
    		->first();
    	$pdf = PDF::loadView('laporan.keuanganpdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->stream('Laporan Keuangan.pdf');
    }

    public function pdfKeuanganAll($masjid_id)
    {
    	$data = SaldoKeuangan::with('history')
    		->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
    		->where('saldo_keuangan.masjid_id', $masjid_id)
    		->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
    		->first();
    	$pdf = PDF::loadView('laporan.keuanganpdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->stream('Laporan Keuangan.pdf');
    }

    public function excelKeuanganAll($masjid_id)
    {
        return Excel::download(new KeuanganExport($masjid_id), 'Laporan Keuangan.xlsx');
    }

    // public function excelKeuangan($masjid_id)
    // {
    // 	$data = SaldoKeuangan::with('history')
    // 		->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
    // 		->where('saldo_keuangan.masjid_id', $masjid_id)
    // 		->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
    // 		->first();
    // 	return Excel::download(new KeuanganExport($data), 'Data Keuangan.xlsx');
    // }

    // public function excelKeuanganAll()
    // {
    // 	$data = SaldoKeuangan::with('history')
    // 		->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
    // 		->where('saldo_keuangan.masjid_id', $masjid_id)
    // 		->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
    // 		->get();
    // 	return Excel::download(new KeuanganExport($data), 'Data Keuangan.xlsx');
    // }
}
