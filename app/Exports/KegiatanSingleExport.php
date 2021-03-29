<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KegiatanSingleExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $id;

	public function __construct($masjid_id, $id)
    {
        $this->masjid_id = $masjid_id;
        $this->id = $id;
    }

    public function view(): View
    {
        return view('laporan.kegiatansingleexcel', [
            'data' => Kegiatan::where('masjid_id', $this->masjid_id)
                        ->where('id', $this->id)
                        ->first()
        ]);
    }
}
