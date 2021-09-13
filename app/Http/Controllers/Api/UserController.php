<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request){
        //dd($request->all());die();
        $user = User::where('email',$request->email)->first();

        if($user){

            if(password_verify($request->password, $user->password)){
                return response()->json([
                    'success' => 1,
                    'message' => 'Selamat datang '.$user->name,
                    'user' => $user
                ]);
            }
            return $this->eror('Password Salah');
            
        }
        return $this->eror('Email Tidak Ditemukan');
    }


    public function register(Request $request){
        //nama,email,password
        $validasi = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6'
            
        ]);

        if($validasi->fails()){
            $val = $validasi->errors()->all();
            return $this->eror($val[0]);

        }

        $user = User::create(array_merge($request->all(),[
            'password' => bcrypt($request->password)
        ]));

        if($user){
            return response()->json([
                'success' => 1,
                'message' => 'Selamat datang Register Berhasil',
                'user'    => $user
            ]);
        }
        return $this->eror('Registrasi Gagal');

        

    }

    public function eror($pesan){
        return response()->json([
            'success' => 0,
            'message' => $pesan
        ]);
    }
}
