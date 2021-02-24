<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Pengguna;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class PenggunaController extends Controller
{
    public function store(Request $request)
    {
	  	$data = $request->all();
	  	$validator = Validator::make($data, [
	  	 'nama' => 'required',
	  	 'username' => 'required|unique:pengguna',
	  	 'password' => 'required',
	  	 'level' => 'required',
	  	]);

	  	if ($validator->fails()) {
	  	    return response()->json([
	  	        'status' => 409,
	  	        'message' => $validator->errors(),
	  	        'data' => false,
	  	    ]);
	  	}
	  	$data['password'] = Hash::make($data['password']);
	  	if ($request->hasFile('foto')) {
            $data['foto'] = (string) Image::make($request->foto)->encode('data-url');
        }
	  	$result = Pengguna::create($data);
	  	$level = Level::create([
	  		'pengguna_id' => $result->id,
	  		'level' => $data['level']
	  	]);
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $result]);
    }

    public function data()
    {
    	$model = Pengguna::with('level');
    	return Datatables::of($model)
    	    ->addColumn('level', function($model) {
    	    	if ($model->level['level'] == 'admin') {
    	    		return '<span class="chip chip-small bg-gray1-dark">
                    		<i class="fa fa-check bg-green1-dark"></i>
                    		<strong class="color-black font-400">Admin</strong>
                			</span>';
    	    	} else {
		    		return '<span class="chip chip-small bg-gray1-dark">
	                		<i class="fa fa-check bg-green1-dark"></i>
	                		<strong class="color-black font-400">Operator</strong>
	            			</span>';
    	    	}
    	    })
    	    ->addColumn('aksi', function($model) {
    	        return '
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark" onclick="lihatData('.$model->id.')"><i class="fa fa-eye"></i></a>
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark" onclick="editData('.$model->id.')"><i class="fa fa-edit"></i></a>
    	        <a href="#" class="btn btn-xxs mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark" onclick="hapusData('.$model->id.')"><i class="fa fa-trash"></i></a>
    	        ';
    	    })
    	    ->addIndexColumn()
    	    ->rawColumns(['aksi', 'level'])
    	    ->make(true);
    }

    public function show($id)
    {
    	$data = Pengguna::with('level')->where('id', $id)->first();
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
    	$data = $request->all();
    	$validator = Validator::make($data, [
    	 'id' => 'required',
    	 'nama' => 'required',
    	 'username' => 'unique:pengguna',
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
    	if ($request->hasFile('foto')) {
            $fileName = $request->foto->getClientOriginalName();
            $path = $request->file('foto')->storeAs('public/pengguna', $fileName);
            $data['foto'] = $path;
        } else {
            unset($data['foto']);
        }
        if (isset($request->password)) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        $result = Pengguna::find($request->id)->update($data);
        $level = Level::where('pengguna_id', $request->id)->update([
					'level' => $request->level
				]);
		if ($result == 1) {
			return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
		} else {
			return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
		}
	  	// if ($json['password']) {
	  	// 	if ($json['foto']) {
			 //  	$json['password'] = Hash::make($json['password']);
			 //  	$data = Pengguna::where('id', $json['id'])->update([
			 //  		'nama' => $json['nama'],
			 //  		'email' => $json['email'],
			 //  		'password' => $json['password'],
			 //  		'foto' => $json['foto'],
			 //  		'masjid_id' => $json['masjid_id']
			 //  	]);
			 //  	$level = Level::where('pengguna_id', $json['id'])->update([
			 //  		'level' => $json['level']
			 //  	]);
	  	// 	} else {
	  	// 		unset($json['foto']);
	  	// 		$data = Pengguna::where('id', $json['id'])->update([
	  	// 			'nama' => $json['nama'],
	  	// 			'email' => $json['email'],
	  	// 			'masjid_id' => $json['masjid_id']
	  	// 		]);
	  	// 		$level = Level::where('pengguna_id', $json['id'])->update([
	  	// 			'level' => $json['level']
	  	// 		]);
	  	// 	}
		  // 	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	  	// } else {
	  	//     unset($json['password']);
	  	//     $data = Pengguna::where('id', $json['id'])->update([
	  	//     	'nama' => $json['nama'],
	  	//     	'email' => $json['email'],
	  	//     	'foto' => $json['foto'],
	  	//     	'masjid_id' => $json['masjid_id']
	  	//     ]);
	  	//     $level = Level::where('pengguna_id', $json['id'])->update([
	  	//     	'level' => $json['level']
	  	//     ]);
		  // 	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
	  	// }
    }

    public function destroy($id)
    {
	  	$data = Pengguna::where('id', $id)->delete();
	  	$level = Level::where('pengguna_id', $id)->delete();
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

    public function logout()
    {
    	# code...
    }
}
