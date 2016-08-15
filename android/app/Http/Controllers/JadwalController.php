<?php
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/10/2016
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\Models\Cabang;
use App\Models\JadwalPengajar;
use App\Models\MapelPengajar;
use App\Models\Pengajar;
use App\Models\TingkatPendidikanPengajar;
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
        $model->status = $model::ACTIVE;
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
        $model->status = JadwalPengajar::DEACTIVE;
        if($model->save()){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }
    public function activeJadwalPengajar(Request $request)
    {
        $jadwal_id = $request->input('jadwal_id');
        $model = JadwalPengajar::find($jadwal_id);
        $model->status = JadwalPengajar::ACTIVE;
        if($model->save()){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getJadwalByMapel(Request $request)
    {
        $user_id = $request->input('user_id');
        $mapel_id = $request->input('mapel_id');
        $user = User::find($user_id);
        $siswa = $user->siswa;
        $mapel_pengajar = MapelPengajar::where('mapel_id',$mapel_id)->with(['pengajar.user','mapel.tingkat'])->get();
        if(count($mapel_pengajar)>0){
            $data = array();
            $i = 0;
            foreach ($mapel_pengajar as $row){
                if($row->pengajar->user->status == 1 && $row->pengajar->zona_id == $siswa->zona_id){
                    $cabang = Cabang::find($row->pengajar->zona_id);
                    $data[$i]['pengajar_id'] = $row->pengajar->id;
                    $data[$i]['mapel_id'] = $mapel_id;
                    $data[$i]['nama_pengajar'] = $row->pengajar->fullname;
                    $data[$i]['label_tempat'] = $cabang->alamat."(".$cabang->nama.")";
                    $data[$i]['label_mapel'] = $row->mapel->nama." - ".$row->mapel->tingkat->nama;
                    $data[$i]['photo'] = $row->pengajar->photo;
                    $i++;
                }
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getPengajar(Request $request)
    {
        $pengajar_id = $request->input('pengajar_id');
        $pengajar = Pengajar::find($pengajar_id);
        if(count($pengajar)>0){
            $tingkat_pendidikan = TingkatPendidikanPengajar::where('pengajar_id',$pengajar_id)->with('tingkat_pendidikan')->get();
            $mapel = MapelPengajar::where('pengajar_id',$pengajar_id)->with('mapel')->get();
            $cabang = Cabang::find($pengajar->zona_id);
            $lecture = "";
            $tingkat_pengajar = "";
            foreach ($tingkat_pendidikan as $tingkat){
                $tingkat_pengajar.=$tingkat->tingkat_pendidikan->nama.", ";
            }
            foreach ($mapel as $row){
                $lecture.=$row->mapel->nama.", ";
            }
            $lecture = substr($lecture, 0, -2);
            $tingkat_pengajar = substr($tingkat_pengajar, 0, -2);
            $pengajar['lecture'] = $lecture;
            $pengajar['jenjang_mengajar'] = $tingkat_pengajar;
            $pengajar['label_cabang'] = $cabang->nama;
            return response()->json(['status'=>1,'data'=>$pengajar]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

}
