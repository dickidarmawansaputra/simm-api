<?php

namespace App\Exports;

use App\Models\Inventaris;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarisSingleExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $id;

	public function __construct($masjid_id, $id)
    {
        $this->masjid_id = $masjid_id;
        $this->id = $id;
    }

    public function view(): View
    {
        return view('laporan.inventarissingleexcel', [
            'inventaris' => Inventaris::where('masjid_id', $this->masjid_id)
                            ->where('id', $this->id)
                            ->first()
        ]);
    }
}
