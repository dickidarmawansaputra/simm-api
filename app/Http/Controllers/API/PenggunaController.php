<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Pengguna;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function store()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
	  	$validator = Validator::make($json, [
	  	 'nama' => 'required',
	  	 'username' => 'required|unique:pengguna',
	  	 'password' => 'required',
	  	 'foto' => 'required',
	  	 'masjid_id' => 'required',
	  	 'level' => 'required',
	  	]);

	  	if ($validator->fails()) {
	  	    return response()->json([
	  	        'status' => 409,
	  	        'message' => $validator->errors(),
	  	        'data' => false,
	  	    ]);
	  	}
	  	$json['password'] = Hash::make($json['password']);
	  	$data = Pengguna::create($json);
	  	$level = Level::create([
	  		'pengguna_id' => $data->id,
	  		'level' => $json['level']
	  	]);
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function data()
    {
    	$model = Pengguna::with('level');
    	return Datatables::of($model)
    	    ->addColumn('aksi', function($model) {
    	        return '
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark" onclick="lihatData('.$model->id.')"><i class="fa fa-eye"></i></a>
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark" onclick="editData('.$model->id.')"><i class="fa fa-edit"></i></a>
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark" onclick="hapusData('.$model->id.')"><i class="fa fa-trash"></i></a>
    	        ';
    	    })
    	    ->addIndexColumn()
    	    ->rawColumns(['aksi'])
    	    ->make(true);
    }

    public function show($id)
    {
    	$data = Pengguna::where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
	  	if ($json['password']) {
	  		if ($json['foto']) {
			  	$json['password'] = Hash::make($json['password']);
			  	$data = Pengguna::where('id', $json['id'])->update([
			  		'nama' => $json['nama'],
			  		'email' => $json['email'],
			  		'password' => $json['password'],
			  		'foto' => $json['foto'],
			  		'masjid_id' => $json['masjid_id']
			  	]);
			  	$level = Level::where('pengguna_id', $json['id'])->update([
			  		'level' => $json['level']
			  	]);
	  		} else {
	  			unset($json['foto']);
	  			$data = Pengguna::where('id', $json['id'])->update([
	  				'nama' => $json['nama'],
	  				'email' => $json['email'],
	  				'masjid_id' => $json['masjid_id']
	  			]);
	  			$level = Level::where('pengguna_id', $json['id'])->update([
	  				'level' => $json['level']
	  			]);
	  		}
		  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	  	} else {
	  	    unset($json['password']);
	  	    $data = Pengguna::where('id', $json['id'])->update([
	  	    	'nama' => $json['nama'],
	  	    	'email' => $json['email'],
	  	    	'foto' => $json['foto'],
	  	    	'masjid_id' => $json['masjid_id']
	  	    ]);
	  	    $level = Level::where('pengguna_id', $json['id'])->update([
	  	    	'level' => $json['level']
	  	    ]);
		  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	  	}
    }

    public function destroy()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
	  	$data = Pengguna::where('id', $json['id'])->delete();
	  	$level = Level::where('pengguna_id', $json['id'])->delete();
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function login()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
	  	if (Auth::attempt($json)) {
	  		$user = Auth::user()->with('level')->first();
	  		$token = $user->createToken('xRB5g1rqBMX3VGELz1CMdg9FlPrgPQ09hsqSsbHr')->accessToken;
	  		return response()->json(['status' => 200, 'message' => 'success', 'data' => $user, 'token' => $token]);
	  	} else {
	  		return response()->json(['status' => 401, 'message' => 'unauthorized', 'data' => false]);
	  	}
    }
}
