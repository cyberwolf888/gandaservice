<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email',$email)
            ->where('password',md5($password))
            ->first();
        if($user){
            if($user->type == User::SISWA){
                $siswa = $user->siswa;
                return response()->json(['status'=>1,'data'=>$siswa]);
            }
            if($user->type == User::PENGAJAR){
                $pengajar = $user->pengajar;
                return response()->json(['status'=>1,'data'=>$pengajar]);
            }
        }else{
            return response()->json(['status'=>0]);
        }
    }

    //
}
