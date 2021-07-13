<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class MasjidController extends Controller
{
	public function show($id)
	{
		$data = Masjid::where('id', $id)->first();
        $data['gambar'] = URL::to('/').''.Storage::url($data['gambar']);
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	}

    public function showAll()
    {
    	$data = Masjid::limit(3)->get();
        foreach ($data as $key => $value) {
            $gambar = URL::to('/').''.Storage::url($value['gambar']);
            $value['gambar'] = $gambar;
        }
    	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAllDetail(Request $request)
    {
        $data = Masjid::where(function ($query) use ($request) {
            if ($request->nama_masjid) {
                $query->where('nama_masjid', 'LIKE', '%'.$request->nama_masjid.'%');
            }
        })
        ->where(function ($query) use ($request) {
            if ($request->kecamatan) {
                $query->where('kecamatan', $request->kecamatan);
            }
        })
        ->get();
        foreach ($data as $key => $value) {
            $gambar = URL::to('/').''.Storage::url($value['gambar']);
            $value['gambar'] = $gambar;
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
