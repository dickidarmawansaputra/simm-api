<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kepengurusan;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KepengurusanController extends Controller
{
    public function show($masjid_id, $id)
    {
        $data = Kepengurusan::leftJoin('masjid', 'kepengurusan.masjid_id', 'masjid.id')
            ->where('kepengurusan.masjid_id', $masjid_id)
            ->where('kepengurusan.id', $id)
            ->select('nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'jabatan', 'periode', 'no_hp', 'email', 'masjid_id', 'masjid.nama_masjid')
            ->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAll($masjid_id)
    {
        $data = Kepengurusan::leftJoin('masjid', 'kepengurusan.masjid_id', 'masjid.id')
            ->where('kepengurusan.masjid_id', $masjid_id)
            ->select('nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'jabatan', 'periode', 'no_hp', 'email', 'masjid_id', 'masjid.nama_masjid')
            ->get();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAllLimit($masjid_id)
    {
        $data = Kepengurusan::leftJoin('masjid', 'kepengurusan.masjid_id', 'masjid.id')
            ->where('kepengurusan.masjid_id', $masjid_id)
            ->select('nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'jabatan', 'periode', 'no_hp', 'email', 'masjid_id', 'masjid.nama_masjid')
            ->limit(3)
            ->get();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
