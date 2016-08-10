<?php
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/10/2016
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\Models\JadwalPengajar;
use App\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
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

    public function tambahJadwalPengajar(Request $request)
    {
        $hari = ['Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6,'Minggu'=>0];
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $pengajar = $user->pengajar;
        $model = new JadwalPengajar();
        $model->pengajar_id = $pengajar->id;
        $model->zona_id = $pengajar->zona_id;
        $model->mapel_id = $request->input('mapel_id');
        $model->hari = $hari[$request->input('hari')];
        $model->jam_mulai = $request->input('waktu_mulai');
        $model->jam_selesai = $request->input('waktu_selesai');
        if($model->save()){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

}
