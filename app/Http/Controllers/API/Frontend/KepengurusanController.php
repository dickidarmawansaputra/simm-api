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
        $data = Kepengurusan::where('masjid_id', $masjid_id)->where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function showAll($masjid_id)
    {
        $data = Kepengurusan::where('masjid_id', $masjid_id)->get();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
