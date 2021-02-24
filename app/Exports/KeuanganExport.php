<?php

namespace App\Exports;

use App\Models\SaldoKeuangan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KeuanganExport implements FromView, ShouldAutoSize
{
	public $masjid_id;

	public function __construct($masjid_id)
    {
        $this->masjid_id = $masjid_id;
    }

    public function view(): View
    {
        return view('laporan.keuanganexcel', [
            'keuangan' => SaldoKeuangan::with('history')
                            ->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
                            ->where('saldo_keuangan.masjid_id', $this->masjid_id)
                            ->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
                            ->first()
        ]);
    }
}
