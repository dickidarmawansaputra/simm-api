<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class PenggunaController extends Controller
{
    public function store()
    {
    	$input = file_get_contents('php://input');
	  	$json = json_decode($input, true);
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
    	$input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = Pengguna::where(function($query) use ($json) {
                    if ($json['level'] == 'operator') {
                        $query->where('masjid_id', $json['masjid_id']);
                    }
                })->paginate(10);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
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
	  	if (Auth::attempt(['email' => $json['email'], 'password' => $json['password']])) {
	  		$data = Auth::user();
	  		return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	  	} else {
	  		return response()->json(['status' => 401, 'message' => 'failed', 'data' => false]);
	  	}
    }
}
