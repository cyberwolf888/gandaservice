<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pengajar;
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
        if($user && count($user)>0){
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

    public function registerPengajar(Request $request)
    {
        $user = User::where('email',$request->input('email'))->count();
        $pengajar = Pengajar::where('pengajar_cp',$request->input('telp'))->count();

        if($user == 0 && $pengajar == 0){
            $cabang = Cabang::where('nama',$request->input('zona'))->first();
            $model = new User();
            $mPengajar = new Pengajar();

            $model->email = $request->input('email');
            $model->password = md5($request->input('password'));
            $model->status = 0;
            $model->type = User::PENGAJAR;
            $model->token = md5($request->input('email'));
            if($model->save()){
                $mPengajar->user_id = $model->id;
                $mPengajar->zona_id = $cabang->id;
                $mPengajar->fullname = $request->input('nama');
                $mPengajar->pengajar_alamat = $request->input('alamat');
                $mPengajar->pengajar_cp = $request->input('telp');
                $mPengajar->pengajar_pendidikan = $request->input('edukasi');
                $mPengajar->status_mengajar = Pengajar::AVALAIBLE;
                if($mPengajar->save()){
                    $msg = "Klik tautan berikut untuk mengaktifkan account anda: \n".route('activation',['token'=>$model->token]);
                    $msg = wordwrap($msg,70);
                    mail($request->input('email'),"Ganda Edukasi - Account Activation",$msg);
                    return response()->json(['status'=>1]);
                }
            }
        }else{
            return response()->json(['status'=>0]);
        }

    }

    public function activation($token)
    {
        $user = User::where('token',$token)->first();
        if($user && count($user)>0){
            $user->status = 1;
            $user->token = null;
            if($user->save()){
                return "Account anda telah aktif!";
            }
        }
    }

    public function getProfle(Request $request,$type)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($type == 'siswa'){
            if($user && count($user)>0){
                $siswa = $user->siswa;
                $siswa['email'] = $user->email;
                return response()->json(['status'=>1,'data'=>$siswa]);
            }else{
                return response()->json(['status'=>0]);
            }
        }elseif ($type == 'pengajar'){
            //TODO proses get profile pengajar
        }
    }

    //
}
