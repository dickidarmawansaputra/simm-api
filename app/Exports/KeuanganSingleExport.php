<?php

namespace App\Exports;

use App\Models\SaldoKeuangan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KeuanganSingleExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $id;

	public function __construct($masjid_id, $id)
    {
        $this->masjid_id = $masjid_id;
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;
        return view('laporan.keuangansingleexcel', [
            'keuangan' => SaldoKeuangan::with(['history' => function ($query) use ($id) {
                                $query->where('id', $id);
                            }])
                            ->leftJoin('masjid', 'saldo_keuangan.masjid_id', 'masjid.id')
                            ->where('saldo_keuangan.masjid_id', $this->masjid_id)
                            ->select('saldo_keuangan.id', 'saldo_keuangan.masjid_id', 'saldo_keuangan.saldo', 'masjid.nama_masjid', 'masjid.tipologi_masjid')
                            ->first()
        ]);
    }
}
