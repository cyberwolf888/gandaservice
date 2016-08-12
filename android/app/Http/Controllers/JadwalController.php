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

    public function getJadwalPengajar(Request $request)
    {
        $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $pengajar = $user->pengajar;
        $jadwal = JadwalPengajar::where('pengajar_id',$pengajar->id)->with(['mapel.tingkat','cabang'])->get();
        if(count($jadwal)>0){
            $data = array();
            foreach ($jadwal as $row){
                $row['label_mapel'] = $row->mapel->nama." - ".$row->mapel->tingkat->nama;
                $row['label_cabang'] = $row->cabang->nama;
                $row['label_hari'] = $hari[$row->hari];
                array_push($data, $row);
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function deleteJadwalPengajar(Request $request)
    {
        $jadwal_id = $request->input('jadwal_id');
        $model = JadwalPengajar::find($jadwal_id);
        if($model->delete()){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getJadwalByMapel(Request $request)
    {
        $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $user_id = $request->input('user_id');
        $mapel_id = $request->input('mapel_id');
        $user = User::find($user_id);
        $siswa = $user->siswa;
        $jadwal = JadwalPengajar::where('mapel_id',$mapel_id)->where('zona_id',$siswa->zona_id)->with(['mapel.tingkat','cabang','pengajar'])->get();
        if(count($jadwal)>0){
            $data = array();
            $i = 0;
            foreach ($jadwal as $row){
                $mData = [
                    'jadwal_id'=>$row->id,
                    'pengajar_id'=>$row->pengajar->id,
                    'mapel_id'=>$row->mapel_id,
                    'nama_pengajar'=>$row->pengajar->fullname,
                    'label_mapel'=>$row->mapel->nama." - ".$row->mapel->tingkat->nama,
                    'label_hari'=>$hari[$row->hari],
                    'waktu_mulai'=>$row->jam_mulai,
                    'waktu_selesai'=>$row->jam_selesai,
                    'label_tempat'=>$row->cabang->alamat."(".$row->cabang->nama.")",
                    'photo'=>$row->pengajar->photo
                ];
                $data[$i] = $mData;
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

}
