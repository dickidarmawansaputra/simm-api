<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\Masjid;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KegiatanExport implements FromView, ShouldAutoSize
{
	public $masjid_id, $waktu_kegiatan, $jenis_kegiatan;

	public function __construct($masjid_id, $waktu_kegiatan, $jenis_kegiatan)
    {
        $this->masjid_id = $masjid_id;
        $this->waktu_kegiatan = $waktu_kegiatan;
        $this->jenis_kegiatan = $jenis_kegiatan;
    }

    public function view(): View
    {
        $waktu_kegiatan = $this->waktu_kegiatan;
        if ($waktu_kegiatan) {
            $tahun = explode("-", $waktu_kegiatan)[0];
        } else {
            $tahun = null;
        }
        $jenis_kegiatan = $this->jenis_kegiatan;
        $masjid = Masjid::where('id', $this->masjid_id)->first();
        return view('laporan.kegiatanexcel', ['tahun' => $tahun, 'masjid' => $masjid, 
            'data' => Kegiatan::where('masjid_id', $this->masjid_id)
                        ->where(function($query) use ($waktu_kegiatan) {
                            if ($waktu_kegiatan) {
                                $query->whereMonth('tanggal_waktu_kegiatan', explode("-", $waktu_kegiatan)[1])
                                      ->whereYear('tanggal_waktu_kegiatan', explode("-", $waktu_kegiatan)[0]);
                            }
                        })
                        ->where(function($query) use ($jenis_kegiatan) {
                            if ($jenis_kegiatan) {
                                $query->where('jenis_kegiatan', $jenis_kegiatan);
                            }
                        })
                        ->get()
        ]);
    }
}
