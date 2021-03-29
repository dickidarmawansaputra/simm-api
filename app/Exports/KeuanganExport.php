<?php

namespace App\Exports;

use App\Models\SaldoKeuangan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KeuanganExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $waktu_keuangan, $jenis_keuangan;

	public function __construct($masjid_id, $waktu_keuangan, $jenis_keuangan)
    {
        $this->masjid_id = $masjid_id;
        $this->waktu_keuangan = $waktu_keuangan;
        $this->jenis_keuangan = $jenis_keuangan;
    }

    public function view(): View
    {
        $waktu_keuangan = $this->waktu_keuangan;
        $jenis_keuangan = $this->jenis_keuangan;
        return view('laporan.keuanganexcel', [
            'keuangan' => SaldoKeuangan::with(['history' => function ($query) use ($jenis_keuangan, $waktu_keuangan) {
                                $query->where(function($query) use ($jenis_keuangan) {
                                    if ($jenis_keuangan) {
                                        $query->where('jenis_keuangan', $jenis_keuangan);
                                    }
                                });
                                $query->where(function($query) use ($waktu_keuangan) {
                                    if ($waktu_keuangan) {
                                        $query->whereMonth('tanggal', explode("-", $waktu_keuangan)[1])
                                              ->whereYear('tanggal', explode("-", $waktu_keuangan)[0]);
                                    }
                                });
                            }])
                            ->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
                            ->where('saldo_keuangan.masjid_id', $this->masjid_id)
                            ->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
                            ->first()
        ]);
    }
}
