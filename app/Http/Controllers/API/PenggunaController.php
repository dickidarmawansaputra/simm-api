<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Pengguna;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

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
            $fileName = str_replace(' ', '-', strtolower($request->foto->getClientOriginalName()));
            $path = $request->file('foto')->storeAs('public/pengguna', $fileName);
            $data['foto'] = $path;
        } else {
        	$data['foto'] = 'public/pengguna/avatar.png';
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
    		->addColumn('foto', function($model) {
                return URL::to('/').''.Storage::url($model['foto']);
            })
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
        $data['foto'] = URL::to('/').''.Storage::url($data['foto']);
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function update(Request $request)
    {
    	$data = $request->all();
    	$validator = Validator::make($data, [
    	 'id' => 'required',
    	 'nama' => 'required',
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
            $fileName = str_replace(' ', '-', strtolower($request->foto->getClientOriginalName()));
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
    }

    public function destroy($id)
    {
	  	$data = Pengguna::find($id);
	  	Storage::delete($data->foto);
        $data->delete();
	  	$level = Level::where('pengguna_id', $id)->delete();
	  	return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
    }

    public function login(Request $request)
    {
	  	$auth = Auth::attempt(['username' => $request->username, 'password' => $request->password]);
	  	if ($auth) {
	  		$user = Auth::user();
	  		$token = $user->createToken($user->password)->accessToken;
	  		return response()->json(['status' => 200, 'message' => 'success', 'token' => $token]);
	  	} else {
	  		return response()->json(['status' => 401, 'message' => 'unauthorized', 'data' => false]);
	  	}
    }

    public function logout(Request $request)
    {
        return $request->user()->token()->revoke();
    }

    public function user(Request $request)
    {
        $user = Auth::user();
        $user['foto'] = URL::to('/').''.Storage::url($user['foto']);
        return response()->json(['status' => 200, 'message' => 'success', 'user' => $user, 'level' => Auth::user()->level['level']]);
    }
}
