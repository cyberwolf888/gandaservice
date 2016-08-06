<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mapel;
use App\Models\MapelPengajar;
use App\Models\Pengajar;
use App\Models\TingkatPendidikan;
use App\Models\TingkatPendidikanPengajar;
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
            ->where('status',1)
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

    public function cekProfilePengajar(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($user && count($user)>0){
            $pengajar = $user->pengajar;
            $isNewAccount = 0;
            $tingkat_pengajar = TingkatPendidikanPengajar::where('pengajar_id',$pengajar->id)->count();
            $mapel_pengajar = MapelPengajar::where('pengajar_id',$pengajar->id)->count();
            if($tingkat_pengajar == 0 && $mapel_pengajar == 0){
                $isNewAccount=1;
            }
            return response()->json(['status'=>1,'isNewAccount'=>$isNewAccount]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function complatingProfile(Request $request)
    {
        $success = false;
        $user = User::find($request->input('user_id'));
        $pengajar = $user->pengajar;
        $tingkat_pendidikan = explode(';', substr($request->input('tingkat_pendidikan'), 0,-1));
        if (count($tingkat_pendidikan)>0){
            foreach ($tingkat_pendidikan as $key=>$mtingkat){
                $model = new TingkatPendidikanPengajar();
                $model->pengajar_id = $pengajar->id;
                $model->tingkat_pendidikan_id = $mtingkat;
                if($model->save()){
                    $success = true;
                }else{
                    $success = false;
                    break;
                }
            }
        }else{
            $model = new TingkatPendidikanPengajar();
            $model->pengajar_id = $pengajar->id;
            $model->tingkat_pendidikan_id = substr($request->input('tingkat_pendidikan'), 0,-1);
            if($model->save()){
                $success = true;
            }else{
                $success = false;
            }
        }


        $mapel = explode(';', substr($request->input('mapel'), 0,-1));
        if (count($tingkat_pendidikan)>0){
            foreach ($mapel as $key=>$mMapel){
                $model = new MapelPengajar();
                $model->pengajar_id = $pengajar->id;
                $model->mapel_id = $mMapel;
                if($model->save()){
                    $success = true;
                }else{
                    $success = false;
                    break;
                }
            }
        }else{
            $model = new MapelPengajar();
            $model->pengajar_id = $pengajar->id;
            $model->mapel_id = substr($request->input('mapel'), 0,-1);
            if($model->save()){
                $success = true;
            }else{
                $success = false;
            }
        }

        if($success){
            return response()->json(['status'=>1]);
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
            $data = array();
            $data['label_tingkat_pendidikan'] = "";
            $data['id_tingkat_pendidikan'] = "";
            $data['label_mapel'] = "";
            $data['id_mapel'] = "";

            $pengajar = $user->pengajar;
            $tingkat_pengajar = TingkatPendidikanPengajar::with('tingkat_pendidikan')->where('pengajar_id',$pengajar->id)->get();
            $mapel_pengajar = MapelPengajar::with('mapel.tingkat')->where('pengajar_id',$pengajar->id)->get();

            foreach ($tingkat_pengajar as $row_tingkat_mengajar){
                $data['label_tingkat_pendidikan'].= $row_tingkat_mengajar->tingkat_pendidikan->nama.", ";
                $data['id_tingkat_pendidikan'].= $row_tingkat_mengajar->tingkat_pendidikan_id.";";
            }
            foreach ($mapel_pengajar as $row_mapel_pengajar){
                $data['label_mapel'].= $row_mapel_pengajar->mapel->nama." - ".$row_mapel_pengajar->mapel->tingkat->nama.", ";
                $data['id_mapel'].= $row_mapel_pengajar->mapel_id.";";
            }
            $data['label_tingkat_pendidikan'] = substr($data['label_tingkat_pendidikan'], 0, -2);
            $data['label_mapel'] = substr($data['label_mapel'], 0, -2);

            $data['fullname'] = $pengajar->fullname;
            $data['email'] = $user->email;
            $data['pengajar_cp'] = $pengajar->pengajar_cp;
            $data['pengajar_pendidikan'] = $pengajar->pengajar_pendidikan;
            $data['pengajar_alamat'] = $pengajar->pengajar_alamat;
            $data['zona_id'] = $pengajar->zona_id;
            $data['zona'] = $pengajar->cabang->nama;
            $data['photo'] = $pengajar->photo;

            return response()->json(['status'=>1,'data'=>$data]);
        }
    }

    public function editProfilePengajar(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($user_id && count($user)){
            $pengajar = $user->pengajar;
            dd($pengajar);
        }
    }

    //
}
