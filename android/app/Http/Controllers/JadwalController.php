<?php
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/10/2016
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\Models\Cabang;
use App\Models\DetailJadwal;
use App\Models\History;
use App\Models\Jadwal;
use App\Models\JadwalPengajar;
use App\Models\Mapel;
use App\Models\MapelPengajar;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Pengajar;
use App\Models\TingkatPendidikanPengajar;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $cekJadwal = Jadwal::whereRaw(DB::raw("pengajar_id = '".$row->pengajar->id."' AND 
                siswa_id = '".$siswa->id."' AND 
                mapel_id = '".$mapel_id."' AND 
                (status='1' OR status='3')"))->count();
                if($cekJadwal>0){
                    continue;
                }
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

    public function getPaket(Request $request)
    {
        $paket = Paket::with('tarif')->get();
        if(count($paket)>0){
            $data = array();
            $i = 0;
            foreach ($paket as $row){
                $data[$i]['paket_id'] = $row->id;
                $data[$i]['tarif_id'] = $row->tarif_id;
                $data[$i]['nama'] = $row->nama;
                $data[$i]['jumlah_pertemuan'] = $row->jumlah_pertemuan;
                $data[$i]['durasi'] = $row->durasi;
                $data[$i]['harga'] = $row->tarif->harga;
                $data[$i]['label_harga'] = number_format($row->tarif->harga, 0, ',', '.');
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function buatJadwal(Request $request)
    {
        $paket = Paket::find($request->input('paket_id'));
        $user = User::find($request->input('user_id'));
        $mapel = Mapel::find($request->input('mapel_id'));
        $siswa = $user->siswa;
        $pengajar = Pengajar::find($request->input('pengajar_id'));
        $jadwal = new Jadwal();

        $jadwal->pengajar_id = $pengajar->id;
        $jadwal->siswa_id = $siswa->id;
        $jadwal->mapel_id = $mapel->id;
        $jadwal->paket_id = $paket->id;
        $jadwal->status = 3;
        if($jadwal->save()){
            $result = true;
            for($i=0; $i<$paket->jumlah_pertemuan; $i++){
                $timestamp = strtotime($request->input("waktu_pertemuan".$i)) + 60*90;
                $waktu_selesai = date('H:i', $timestamp);
                if($i>0){
                    $waktu_pertemuan = date("Y-m-d H:i:s",strtotime($request->input("tgl_pertemuan" . $i)));
                    $x = $i-1;
                    $old_time = date("Y-m-d H:i:s",strtotime($request->input("tgl_pertemuan" . $x)));
                    if($old_time>=$waktu_pertemuan){
                        $result = false;
                        $xi = $i+1;
                        $error = "Tanggal pertemuan ".$xi." salah!";
                        break;
                    }
                }
                $detail_jadwal = new DetailJadwal();
                $detail_jadwal->jadwal_id = $jadwal->id;
                $detail_jadwal->pertemuan = $i+1;
                $detail_jadwal->tgl_pertemuan = date("Y-m-d",strtotime($request->input("tgl_pertemuan" . $i)));
                $detail_jadwal->waktu_mulai = $request->input("waktu_pertemuan".$i);
                $detail_jadwal->waktu_selesai = $waktu_selesai;
                $detail_jadwal->tempat = $request->input("tempat_pertemuan".$i);
                if($detail_jadwal->save()){
                    $result = true;
                }else{
                    $result = false;
                    $error = "Gagal menyimpan jadwal.";
                    break;
                }
            }
            if($result){
                return response()->json(['status'=>1]);
            }else{
                DetailJadwal::where('jadwal_id', $jadwal->id)->delete();
                $jadwal->delete();

                return response()->json(['status'=>0,'error'=>$error]);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Gagal menyimpan jadwal.']);
        }
    }

    public function getRequestSiswa(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $pengajar = $user->pengajar;
        $jadwal = Jadwal::where('pengajar_id',$pengajar->id)->where('status','3')->with(['siswa','mapel.tingkat'])->get();
        if(count($jadwal)>0){
            $data = array();
            $i = 0;
            foreach ($jadwal as $row){
                $data[$i]['jadwal_id'] = $row->id;
                $data[$i]['siswa_id'] = $row->siswa_id;
                $data[$i]['mapel_id'] = $row->mapel_id;
                $data[$i]['nama_siswa'] = $row->siswa->fullname;
                $data[$i]['label_mapel'] = $row->mapel->nama." - ".$row->mapel->tingkat->nama;
                $data[$i]['photo'] = $row->siswa->photo;
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0,'error'=>'Tidak ada jadwal yang ditemukan.']);
        }
    }

    public function getDetailRequestSiswa(Request $request)
    {
        $jadwal = Jadwal::find($request->input('jadwal_id'));
        $detail = $jadwal->detail_jadwal;
        if($detail){
            $m_detail = array();
            $i=0;
            foreach ($detail as $row){
                $m_detail[$i]['id'] = $row->id;
                $m_detail[$i]['label_mapel'] = $jadwal->mapel->nama." - ".$jadwal->mapel->tingkat->nama;
                $m_detail[$i]['label_tanggal'] = date("l, d-m-Y", strtotime($row->tgl_pertemuan));
                $m_detail[$i]['label_waktu'] = date("H:i",strtotime($row->waktu_mulai))." WITA";
                $m_detail[$i]['label_tempat'] = $row->tempat;
                $i++;
            }


            $data = array();
            $data['nama_siswa'] = $jadwal->siswa->fullname;
            $data['no_telp'] = $jadwal->siswa->siswa_cp;
            $data['photo'] = $jadwal->siswa->photo;
            $data['detail'] = $m_detail;

            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function tolakJadwal(Request $request)
    {
        $jadwal = Jadwal::find($request->input('jadwal_id'));
        $jadwal->status = 2;
        if($jadwal->save()){
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function terimaJadwal(Request $request)
    {
        $jadwal = Jadwal::find($request->input('jadwal_id'));
        foreach ($jadwal->detail_jadwal as $row){
            $cekJadwal = $this->cekJadwalBentrok($row->tgl_pertemuan, $row->waktu_mulai, $row->waktu_selesai);
            if(count($cekJadwal)>0){
                return response()->json([
                    'status'=>0,
                    'error'=>'Jadwal pertemuan '.$row->pertemuan
                        .' tanggal '.date("d-m-Y",strtotime($row->tgl_pertemuan))
                        .' jam '.date("H:i",strtotime($row->waktu_mulai))
                        .' WITA bentrok dengan jadwal '.$cekJadwal[0]->fullname
                        .' jam '.date("H:i",strtotime($cekJadwal[0]->waktu_mulai))
                        .' WITA'
                ]);
            }
        }
        $jadwal->status = 1;
        if($jadwal->save()){
            $pembayaran = new Pembayaran();
            $pembayaran->siswa_id = $jadwal->siswa_id;
            $pembayaran->jadwal_id = $jadwal->id;
            $pembayaran->jenis_tagihan = Pembayaran::PROGRAM;
            $pembayaran->jumlah = $jadwal->paket->tarif->harga;
            $pembayaran->pembayaran_metode = Pembayaran::TRANSFER_BANK;
            $pembayaran->pembayaran_status = Pembayaran::PROSES;
            if($pembayaran->save()){
                return response()->json(['status'=>1]);
            }else{
                $jadwal->status = 3;
                $jadwal->save();
                return response()->json(['status'=>0]);
            }

        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function cekJadwalBentrok($tgl,$waktu_mulai,$waktu_selesai)
    {
        $timestamp1 = strtotime($waktu_mulai) - 60*90;
        $timestamp2 = strtotime($waktu_selesai) + 60*90;

        $waktu_mulai = date('H:i:s', $timestamp1);
        $waktu_selesai = date('H:i:s', $timestamp2);

        $model = DB::select('SELECT 
                                    dj.*,j.status, s.fullname  
                                FROM `tb_detail_jadwal` AS dj 
                                JOIN tb_jadwal AS j ON dj.jadwal_id = j.id 
                                JOIN tb_siswa AS s ON j.siswa_id = s.id 
                                WHERE 
                                    j.status = 1 AND 
                                    dj.tgl_pertemuan = "'.$tgl.'" AND 
                                    waktu_mulai>"'.$waktu_mulai.'" AND 
                                    waktu_selesai<"'.$waktu_selesai.'"');
        return $model;
    }

    public function getJadwalForHistory(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $pengajar = $user->pengajar;
        $model = DB::select('SELECT 
                                    dj.*,
                                    j.status,j.pengajar_id,
                                    s.fullname,s.photo,s.siswa_cp, 
                                    m.nama AS nama_mapel, 
                                    t.nama AS nama_tingkat, 
                                    h.id AS history_id 
                                FROM tb_detail_jadwal AS dj 
                                JOIN tb_jadwal AS j ON dj.jadwal_id=j.id 
                                JOIN tb_siswa AS s ON j.siswa_id=s.id 
                                JOIN tb_mapel AS m ON j.mapel_id=m.id 
                                JOIN tb_tingkat_pendidikan AS t ON m.tingkat_pendidikan=t.id 
                                LEFT JOIN tb_history AS h ON h.detail_jadwal_id = dj.id 
                                WHERE 
                                    h.id IS NULL AND 
                                    j.status = "1" AND 
                                    j.pengajar_id = "'.$pengajar->id.'"
                                ORDER BY tgl_pertemuan ASC, waktu_mulai ASC');
        if (count($model)>0){
            $data = array();
            $i = 0;
            foreach ($model as $row){
                $data[$i]['jadwal_id'] = $row->jadwal_id;
                $data[$i]['detail_jadwal_id'] = $row->id;
                $data[$i]['nama_siswa'] = $row->fullname;

                $data[$i]['no_telp'] = $row->siswa_cp;
                $data[$i]['photo'] = $row->photo;
                $data[$i]['label_mapel'] = $row->nama_mapel." - ".$row->nama_tingkat;
                $data[$i]['label_tanggal'] =  date("l, d-m-Y", strtotime($row->tgl_pertemuan));
                $data[$i]['label_waktu'] = date("H:i", strtotime($row->waktu_mulai))." WITA";
                $data[$i]['label_tempat'] = $row->tempat;
                $data[$i]['pertemuan'] = $row->pertemuan;
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function createHistory(Request $request){
        $jadwal = Jadwal::find($request->input('jadwal_id'));

        $model = new History();
        $model->detail_jadwal_id = $request->input('detail_jadwal_id');
        $model->siswa_id = $jadwal->siswa_id;
        $model->pengajar_id = $jadwal->pengajar_id;
        $model->history_keterangan = $request->input('keterangan');
        $model->tambahan_jam = $request->input('kelebihanWaktu');
        if($model->save()){
            //TODO proses pembayaran
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0,'error'=>'Gagal menyimpan data.']);
        }

    }

}
