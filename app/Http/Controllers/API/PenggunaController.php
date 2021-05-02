<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\Level;
use App\Models\Pengguna;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    	$data = Pengguna::with('level')->get();
    	if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['foto'] = URL::to('/').''.Storage::url($value->foto);
            }
            return response()->json(['status' => 200, 'message' => 'success', 'data' => $data]);
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => null]);
        }
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

    public function resetVerification(Request $request)
    {
        $check = Pengguna::where('email', $request->email)->exists();
        if ($check) {
            $code = Str::random(5);
            $data = Pengguna::where('email', $request->email)->update(['kode_verifikasi' => $code]);
            $mail = Mail::to($request->email)->send(new ResetPassword($request->email, $code));
            if (count(Mail::failures()) > 0) {
                return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
            } else {
                return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'not found!', 'data' => false]);
        }
    }

    public function resetPassword(Request $request)
    {
        $data = Pengguna::where('email', $request->email)
            ->where('kode_verifikasi', $request->code)
            ->first();
        if ($data) {
            $password = Hash::make($request->password);
            $reset = Pengguna::where('id', $data->id)->update(['password' => $password]);
            if ($reset == 1) {
                return response()->json(['status' => 200, 'message' => 'success', 'data' => true]);
            } else {
                return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
            }
        } else {
            return response()->json(['status' => 400, 'message' => 'bad request!', 'data' => false]);
        }
    }
}
