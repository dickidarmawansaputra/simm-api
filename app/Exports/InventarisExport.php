<?php

namespace App\Exports;

use App\Models\Inventaris;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventarisExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $nama_inventaris, $kondisi_inventaris;

	public function __construct($masjid_id, $nama_inventaris, $kondisi_inventaris)
    {
        $this->masjid_id = $masjid_id;
        $this->nama_inventaris = $nama_inventaris;
        $this->kondisi_inventaris = $kondisi_inventaris;
    }

    public function view(): View
    {
        $nama_inventaris = $this->nama_inventaris;
        $kondisi_inventaris = $this->kondisi_inventaris;
        return view('laporan.inventarisexcel', [
            'data' => Inventaris::where('masjid_id', $this->masjid_id)
                        ->where(function($query) use ($nama_inventaris) {
                            if ($nama_inventaris) {
                                $query->where('nama_inventaris', 'LIKE', '%'.$nama_inventaris.'%');
                            }
                        })
                        ->where(function($query) use ($kondisi_inventaris) {
                            if ($kondisi_inventaris) {
                                $query->where('kondisi_inventaris', $kondisi_inventaris);
                            }
                        })
                        ->get()
        ]);
    }
}
