<?php

namespace App\Http\Controllers\API;

use App\Exports\InventarisExport;
use App\Exports\InventarisSingleExport;
use App\Exports\KegiatanExport;
use App\Exports\KegiatanSingleExport;
use App\Exports\KegiatansExport;
use App\Exports\KeuanganExport;
use App\Exports\KeuanganSingleExport;
use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Kegiatan;
use App\Models\Keuangan;
use App\Models\SaldoKeuangan;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Http\Request;
use PDF;

class LaporanController extends Controller
{
    public function dataInventaris(Request $request)
    {
        $model = Inventaris::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->nama_inventaris) {
                        $query->where('nama_inventaris', 'LIKE', '%'.$request->nama_inventaris.'%');
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->kondisi_inventaris) {
                        $query->where('kondisi_inventaris', $request->kondisi_inventaris);
                    }
                })
                ->get();
        return Datatables::of($model)
            ->addColumn('aksi', function($model) use ($request) {
                return '
                <a href="'.route('excel.inventaris', [$request->masjid_id, $model->id]).'" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"><i class="fa fa-file-excel"></i></a>
                <a href="'.route('pdf.inventaris', [$request->masjid_id, $model->id]).'" target="_blank" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark"><i class="fa fa-file-pdf"></i></a>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function dataKegiatan(Request $request)
    {
        $model = Kegiatan::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->waktu_kegiatan) {
                        $query->whereMonth('tanggal_waktu_kegiatan', explode("-", $request->waktu_kegiatan)[1])
                              ->whereYear('tanggal_waktu_kegiatan', explode("-", $request->waktu_kegiatan)[0]);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->jenis_kegiatan) {
                        $query->where('jenis_kegiatan', $request->jenis_kegiatan);
                    }
                })
                ->get();
        return Datatables::of($model)
            ->addColumn('tanggal_waktu_kegiatan', function($model) {
                return Carbon::parse($model->tanggal_waktu_kegiatan)->format('d F Y H:i:s');
            })
            ->addColumn('aksi', function($model) use ($request) {
                return '
                <a href="'.route('excel.kegiatan', [$request->masjid_id, $model->id]).'" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"><i class="fa fa-file-excel"></i></a>
                <a href="'.route('pdf.kegiatan', [$request->masjid_id, $model->id]).'" target="_blank" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark"><i class="fa fa-file-pdf"></i></a>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['aksi', 'tanggal_waktu_kegiatan'])
            ->make(true);
    }

    public function dataKeuangan(Request $request)
    {
        $model = Keuangan::with('saldo')
                ->where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->waktu_keuangan) {
                        $query->whereMonth('tanggal', explode("-", $request->waktu_keuangan)[1])
                              ->whereYear('tanggal', explode("-", $request->waktu_keuangan)[0]);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->jenis_keuangan) {
                        $query->where('jenis_keuangan', $request->jenis_keuangan);
                    }
                })
                ->get();
        return Datatables::of($model)
            ->addColumn('jenis_keuangan', function($model) use ($request) {
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
                <a href="'.route('excel.keuangan', [$request->masjid_id, $model->id]).'" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"><i class="fa fa-file-excel"></i></a>
                <a href="'.route('pdf.keuangan', [$request->masjid_id, $model->id]).'" target="_blank" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark"><i class="fa fa-file-pdf"></i></a>
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['jenis_keuangan', 'aksi', 'jumlah', 'saldo'])
            ->make(true);
    }

    public function pdfKeuangan($masjid_id, $id)
    {
        $data = SaldoKeuangan::with(['history' => function ($query) use ($id) {
                $query->where('id', $id);
            }])
            ->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
            ->where('saldo_keuangan.masjid_id', $masjid_id)
            ->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
            ->first();
    	$pdf = PDF::loadView('laporan.keuangansinglepdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Keuangan.pdf');
    }

    public function pdfKegiatan($masjid_id, $id)
    {
        $data = Kegiatan::where('masjid_id', $masjid_id)
            ->where('id', $id)
            ->first();
        $pdf = PDF::loadView('laporan.kegiatansinglepdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Kegiatan.pdf');
    }

    public function pdfInventaris($masjid_id, $id)
    {
        $data = Inventaris::where('masjid_id', $masjid_id)
            ->where('id', $id)
            ->first();
        $pdf = PDF::loadView('laporan.inventarissinglepdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Inventaris.pdf');
    }

    public function pdfKeuanganAll(Request $request, $masjid_id)
    {
    	$data = SaldoKeuangan::with(['history' => function ($query) use ($request) {
                $query->where(function($query) use ($request) {
                    if ($request->jenis_keuangan) {
                        $query->where('jenis_keuangan', $request->jenis_keuangan);
                    }
                });
                $query->where(function($query) use ($request) {
                    if ($request->waktu_keuangan) {
                        $query->whereMonth('tanggal', explode("-", $request->waktu_keuangan)[1])
                              ->whereYear('tanggal', explode("-", $request->waktu_keuangan)[0]);
                    }
                });
            }])
    		->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
    		->where('saldo_keuangan.masjid_id', $masjid_id)
    		->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
    		->first();
    	$pdf = PDF::loadView('laporan.keuanganpdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Keuangan.pdf');
    }

    public function pdfKegiatanAll(Request $request, $masjid_id)
    {
        $data = Kegiatan::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->waktu_kegiatan) {
                        $query->whereMonth('tanggal_waktu_kegiatan', explode("-", $request->waktu_kegiatan)[1])
                              ->whereYear('tanggal_waktu_kegiatan', explode("-", $request->waktu_kegiatan)[0]);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->jenis_kegiatan) {
                        $query->where('jenis_kegiatan', $request->jenis_kegiatan);
                    }
                })
                ->get();
        $pdf = PDF::loadView('laporan.kegiatanpdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Kegiatan.pdf');
    }

    public function pdfInventarisAll(Request $request, $masjid_id)
    {
        $data = Inventaris::where(function($query) use ($request) {
                    if ($request->level == 'operator') {
                        $query->where('masjid_id', $request->masjid_id);
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->nama_inventaris) {
                        $query->where('nama_inventaris', 'LIKE', '%'.$request->nama_inventaris.'%');
                    }
                })
                ->where(function($query) use ($request) {
                    if ($request->kondisi_inventaris) {
                        $query->where('kondisi_inventaris', $request->kondisi_inventaris);
                    }
                })
                ->get();
        $pdf = PDF::loadView('laporan.inventarispdf', compact('data'))->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->download('Laporan Inventaris.pdf');
    }

    public function excelKeuangan($masjid_id, $id)
    {
        return Excel::download(new KeuanganSingleExport($masjid_id, $id), 'Laporan Keuangan.xlsx');
    }

    public function excelKegiatan($masjid_id, $id)
    {
        return Excel::download(new KegiatanSingleExport($masjid_id, $id), 'Laporan Kegiatan.xlsx');
    }

    public function excelInventaris($masjid_id, $id)
    {
        return Excel::download(new InventarisSingleExport($masjid_id, $id), 'Laporan Inventaris.xlsx');
    }

    public function excelKeuanganAll(Request $request, $masjid_id)
    {
        $waktu_keuangan = $request->waktu_keuangan;
        $jenis_keuangan = $request->jenis_keuangan;
        return Excel::download(new KeuanganExport($masjid_id, $waktu_keuangan, $jenis_keuangan), 'Laporan Keuangan.xlsx');
    }

    public function excelKegiatanAll(Request $request, $masjid_id)
    {
        $waktu_kegiatan = $request->waktu_kegiatan;
        $jenis_kegiatan = $request->jenis_kegiatan;
        return Excel::download(new KegiatanExport($masjid_id, $waktu_kegiatan, $jenis_kegiatan), 'Laporan Kegiatan.xlsx');
    }

    public function excelInventarisAll(Request $request, $masjid_id)
    {
        $nama_inventaris = $request->nama_inventaris;
        $kondisi_inventaris = $request->kondisi_inventaris;
        return Excel::download(new InventarisExport($masjid_id, $nama_inventaris, $kondisi_inventaris), 'Laporan Inventaris.xlsx');
    }
}
