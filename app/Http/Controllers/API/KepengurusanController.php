<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kepengurusan;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KepengurusanController extends Controller
{
    public function store(Request $request)
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
    	$data = Kepengurusan::create($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Kepengurusan::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function show($id)
    {
    	$data = Kepengurusan::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Kepengurusan::where('id', $json['id'])->update($json);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function destroy($id)
    {
    	$data = Kepengurusan::find($id)->delete();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }
}
