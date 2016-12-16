<?php
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/10/2016
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\Models\Cabang;
use App\Models\CheckRating;
use App\Models\DetailJadwal;
use App\Models\History;
use App\Models\Jadwal;
use App\Models\JadwalPengajar;
use App\Models\Mapel;
use App\Models\MapelPengajar;
use App\Models\Notif;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Models\Pengajar;
use App\Models\Rating;
use App\Models\Siswa;
use App\Models\Tarif;
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
                $cekJadwal = Jadwal::whereRaw(DB::raw("pengajar_id = '".$row->pengajar_id."' AND
                siswa_id = '".$siswa->id."' AND 
                mapel_id = '".$mapel_id."' AND 
                (status='1' OR status='3')"))->count();
                if($cekJadwal>0){
                    continue;
                }
                if($row->pengajar->user->status == 1 && $row->pengajar->zona_id != null){
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
                $lecture.=$row->mapel->nama."-".$row->mapel->tingkat->nama.", ";
            }
            $lecture = substr($lecture, 0, -2);
            $tingkat_pengajar = substr($tingkat_pengajar, 0, -2);
            $pengajar['lecture'] = $lecture;
            $pengajar['jenjang_mengajar'] = $tingkat_pengajar;
            $pengajar['label_cabang'] = $cabang->nama;
            $pengajar['rating'] = $pengajar->getRating();
            return response()->json(['status'=>1,'data'=>$pengajar]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getPaket(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $siswa = $user->siswa;
        $pengajar = Pengajar::find($request->input('pengajar_id'));
        $paket = Paket::with('tarif')->where('tingkat_pendidikan',$siswa->siswa_pendidikan)->get();
        $rating = $pengajar->getRating();
        $bonus = Tarif::getTarifByRate($rating);

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
                $data[$i]['bonus'] = $bonus;
                $data[$i]['total'] = $row->tarif->harga+$bonus;
                $data[$i]['rating'] = $rating;
                $data[$i]['label_harga'] = "Harga Paket: ".number_format($row->tarif->harga, 0, ',', '.');
                $data[$i]['label_bonus'] = $bonus == 0 ? "Tarif Pengajar Rating $rating : GRATIS" : "Tarif Pengajar Rating $rating : Rp. ".number_format($bonus, 0, ',', '.');
                $data[$i]['label_total'] = "Total : Rp.".number_format($row->tarif->harga+$bonus, 0, ',', '.');
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
                $timestamp = strtotime($request->input("waktu_pertemuan".$i)) + 60*$paket->durasi;
                $waktu_selesai = date('H:i', $timestamp);
                if($i>0){
                    $waktu_pertemuan = date("Y-m-d H:i:s",strtotime($request->input("tgl_pertemuan" . $i)));
                    $x = $i-1;
                    $old_time = date("Y-m-d H:i:s",strtotime($request->input("tgl_pertemuan" . $x)));
                    if($old_time>=$waktu_pertemuan){
                        $result = false;
                        $xi = $i+1;
                        $error = "Tanggal pertemuan ".$xi." tidak tersedia.";
                        break;
                    }
                }
                $detail_jadwal = new DetailJadwal();
                $detail_jadwal->jadwal_id = $jadwal->id;
                $detail_jadwal->pengajar_id = $pengajar->id;
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
                //notifikasi ke pengajar ada request baru
                $notif = Notif::where('user_id',$pengajar->user_id)->first();
                if(count($notif)>0){
                    $onesignal = new \App\Plugins\OneSignal();
                    $onesignal->app_type = \App\Plugins\OneSignal::PENGAJAR;
                    $onesignal->title = "Edukezy Teacher";
                    $onesignal->message = "Ada request siswa baru.";
                    $onesignal->sendMessageTo([$notif->onesignal_id]);
                }
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
                $m_detail[$i]['label_tanggal'] = Jadwal::getLabelTanggal($row->tgl_pertemuan);
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
            $cekJadwal = $this->cekJadwalBentrok($row->tgl_pertemuan, $row->waktu_mulai, $row->waktu_selesai, $jadwal->pengajar_id);
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
            $pengajar = Pengajar::find($jadwal->pengajar_id);
            $paket = Paket::find($jadwal->paket_id);
            $rating = $pengajar->getRating();
            $bonus = Tarif::getTarifByRate($rating);

            $pembayaran = new Pembayaran();
            $pembayaran->siswa_id = $jadwal->siswa_id;
            $pembayaran->jadwal_id = $jadwal->id;
            $pembayaran->jenis_tagihan = Pembayaran::PROGRAM;
            $pembayaran->jumlah = $jadwal->paket->tarif->harga+$bonus;
            $pembayaran->pembayaran_metode = Pembayaran::CASH;
            $pembayaran->pembayaran_status = Pembayaran::PROSES;
            $pembayaran->keterangan = "Pemabayaran paket ".$paket->nama." dan pengajar dengan rating ".$rating;
            if($pembayaran->save()){
                //notifikasi ke siswa jadwal ditererima dan pembayaran
                $notif = Notif::where('user_id',$jadwal->siswa->user_id)->first();
                if(count($notif)>0){
                    $onesignal = new \App\Plugins\OneSignal();
                    $onesignal->app_type = \App\Plugins\OneSignal::SISWA;
                    $onesignal->title = "Edukezy";
                    $onesignal->message = "Jadwal anda telah diterima dan ada pembayaran baru.";
                    $onesignal->sendMessageTo([$notif->onesignal_id]);
                }
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

    public function cekJadwalBentrok($tgl,$waktu_mulai,$waktu_selesai,$pengajar_id)
    {
        $timestamp1 = strtotime($waktu_mulai) - 60*90;
        $timestamp2 = strtotime($waktu_selesai) + 60*90;

        $waktu_mulai = date('H:i:s', $timestamp1);
        $waktu_selesai = date('H:i:s', $timestamp2);

        $model = DB::select('SELECT 
                                    dj.*,j.status, s.fullname  
                                FROM tb_detail_jadwal AS dj
                                JOIN tb_jadwal AS j ON dj.jadwal_id = j.id 
                                JOIN tb_siswa AS s ON j.siswa_id = s.id 
                                WHERE 
                                    j.status = 1 AND
                                    dj.pengajar_id = "'.$pengajar_id.'" AND
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
                                    j.status,j.pengajar_id AS j_pengajar_id,
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
                                    dj.tgl_pertemuan = "'.date('Y-m-d').'" AND 
                                    h.id IS NULL AND 
                                    j.status = "1" AND 
                                    dj.pengajar_id = "'.$pengajar->id.'"
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
                $data[$i]['label_tanggal'] =  Jadwal::getLabelTanggal($row->tgl_pertemuan);
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

    private function hitungJarak($latOri,$longOri,$latDes,$longDes){
        $key = env("DISTANCE_MATRIX_KEY");
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$latOri,$longOri&destinations=$latDes,$longDes&mode=driving&language=id&key=$key";
        $result = json_decode(file_get_contents($url),true);
        if($result['status']=="OK"){
            if($result['rows'][0]['elements'][0]['status'] == "OK"){
                return $result['rows'][0]['elements'][0]['distance']['value'];
            }else{
                return 0;
            }

        }
    }

    public function testHitungJarak(){
        $jarak = $this->hitungJarak("-8.647446", "115.233205", "-8.651975","115.237289");
        //return ;
        return Tarif::where('tipe','T3')->where('keterangan','1')->first()->harga*round($jarak/100, 0, PHP_ROUND_HALF_UP);
    }

    public function createHistory(Request $request){
        $jadwal = Jadwal::find($request->input('jadwal_id'));
        $detail = DetailJadwal::find($request->input('detail_jadwal_id'));
        $paket = $jadwal->paket;
        $model = new History();
        $model->detail_jadwal_id = $request->input('detail_jadwal_id');
        $model->siswa_id = $jadwal->siswa_id;
        $model->pengajar_id = $jadwal->pengajar_id;
        $model->history_keterangan = $request->input('keterangan');
        $model->tambahan_jam = $request->input('kelebihanWaktu');
        if($model->save()){
            //Rating
            if($detail->pertemuan==$paket->jumlah_pertemuan){
                $jadwal->status = 2;
                $jadwal->save();

                $rating = new CheckRating();
                $rating->siswa_id = $jadwal->siswa_id;
                $rating->pengajar_id = $jadwal->pengajar_id;
                $rating->jadwal_id = $jadwal->id;
                $rating->save();
            }

            //bayar kelebihan jam
            if($model->tambahan_jam>0){
                $pengajar = Pengajar::find($model->pengajar_id);
                $siswa = Siswa::find($model->siswa_id);
                //$rating = $pengajar->getRating();
                $lebih = round($model->tambahan_jam/5, 0, PHP_ROUND_HALF_UP);
                //$tarif = Tarif::getTarifByRate($rating);
                $tarif = 3000;
                $biaya = $tarif*$lebih;
                $pembayaran = new Pembayaran();
                $pembayaran->siswa_id = $model->siswa_id;
                $pembayaran->pengajar_id = $model->pengajar_id;
                $pembayaran->history_id = $model->id;
                $pembayaran->jadwal_id = $jadwal->id;
                $pembayaran->jenis_tagihan = Pembayaran::JADWAL;
                $pembayaran->jumlah = $biaya;
                $pembayaran->keterangan = "Kelebihan ".$model->tambahan_jam." menit.";
                $pembayaran->pembayaran_metode = Pembayaran::CASH;
                $pembayaran->pembayaran_status = Pembayaran::PROSES;
                $pembayaran->save();

                //tagihan jarak jika zona siswa dan pengajar berbeda
                if($pengajar->zona_id != $siswa->zona_id){
                    $cabang = Cabang::find($pengajar->zona_id);
                    $jarak = $this->hitungJarak($cabang->latitude, $cabang->longitude, $siswa->latitude,$siswa->longitude);
                    $jarak = round($jarak/100, 0, PHP_ROUND_HALF_UP);

                    //tambahkan tagihan jarak jika lebih dari 10km
                    if($jarak>10){
                        //$tarif = Tarif::where('tipe','T3')->where('keterangan','1')->first()->harga;
                        $tarif = 1000;
                        $total = $tarif*($jarak-10);
                        $pembayaran = new Pembayaran();
                        $pembayaran->siswa_id = $model->siswa_id;
                        $pembayaran->pengajar_id = $model->pengajar_id;
                        $pembayaran->history_id = $model->id;
                        $pembayaran->jadwal_id = $jadwal->id;
                        $pembayaran->jenis_tagihan = Pembayaran::JARAK;
                        $pembayaran->jumlah = $total;
                        $pembayaran->keterangan = "Biaya jarak tempuh ".$jarak." km.";
                        $pembayaran->pembayaran_metode = Pembayaran::CASH;
                        $pembayaran->pembayaran_status = Pembayaran::PROSES;
                        $pembayaran->save();
                    }

                }
            }

            //notifikasi ke siswa ada pembayaran jadwal
            $notif = Notif::where('user_id',$jadwal->siswa->user_id)->first();
            if(count($notif)>0){
                $onesignal = new \App\Plugins\OneSignal();
                $onesignal->app_type = \App\Plugins\OneSignal::SISWA;
                $onesignal->title = "Edukezy";
                $onesignal->message = "Ada history pertemuan baru.";
                $onesignal->sendMessageTo([$notif->onesignal_id]);
            }

            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0,'error'=>'Gagal menyimpan data.']);
        }

    }

    public function getHistoryPengajar(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $pengajar = $user->pengajar;
        $model = DB::select('SELECT 
                                    dj.*,
                                    j.status,j.pengajar_id as j_pengajar_id,
                                    s.fullname,s.photo,s.siswa_cp, 
                                    m.nama AS nama_mapel, 
                                    t.nama AS nama_tingkat, 
                                    h.id AS history_id,history_keterangan,tambahan_jam  
                                FROM tb_detail_jadwal AS dj 
                                JOIN tb_jadwal AS j ON dj.jadwal_id=j.id 
                                JOIN tb_siswa AS s ON j.siswa_id=s.id 
                                JOIN tb_mapel AS m ON j.mapel_id=m.id 
                                JOIN tb_tingkat_pendidikan AS t ON m.tingkat_pendidikan=t.id 
                                JOIN tb_history AS h ON h.detail_jadwal_id = dj.id 
                                WHERE 
                                    h.pengajar_id = "'.$pengajar->id.'"
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
                $data[$i]['label_tanggal'] = Jadwal::getLabelTanggal($row->tgl_pertemuan);
                $data[$i]['label_waktu'] = date("H:i", strtotime($row->waktu_mulai))." WITA";
                $data[$i]['label_tempat'] = $row->tempat;
                $data[$i]['pertemuan'] = $row->pertemuan;
                $data[$i]['keterangan'] = $row->history_keterangan;
                $data[$i]['tambahan_jam'] = $row->tambahan_jam;
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getJadwalLesPengajar(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $pengajar = $user->pengajar;
        $model = DB::select('SELECT 
                                    dj.*,
                                    j.status,j.pengajar_id AS j_pengajar_id,
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
                                    dj.pengajar_id = "'.$pengajar->id.'" AND
                                    dj.tgl_pertemuan > "'.date("Y-m-d").'"
                                ORDER BY tgl_pertemuan ASC, waktu_mulai ASC');
        if (count($model)>0){
            $data = array();
            $i = 0;
            foreach ($model as $row){
                $data[$i]['id'] = $row->id;
                $data[$i]['jadwal_id'] = $row->jadwal_id;
                $data[$i]['detail_jadwal_id'] = $row->id;
                $data[$i]['nama_siswa'] = $row->fullname;
                $data[$i]['no_telp'] = $row->siswa_cp;
                $data[$i]['photo'] = $row->photo;
                $data[$i]['label_mapel'] = $row->nama_mapel." - ".$row->nama_tingkat;
                $data[$i]['label_tanggal'] = Jadwal::getLabelTanggal($row->tgl_pertemuan);
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

    public function getHistorySiswa(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $siswa = $user->siswa;
        $model = DB::select('SELECT 
                                    dj.*,
                                    j.status,j.pengajar_id AS j_pengajar_id,j.siswa_id,
                                    p.fullname,p.photo,p.pengajar_cp, 
                                    m.nama AS nama_mapel, 
                                    t.nama AS nama_tingkat, 
                                    h.id AS history_id,history_keterangan,tambahan_jam,
                                    pk.durasi 
                                FROM tb_detail_jadwal AS dj 
                                JOIN tb_jadwal AS j ON dj.jadwal_id=j.id 
                                JOIN tb_pengajar AS p ON dj.pengajar_id=p.id
                                JOIN tb_mapel AS m ON j.mapel_id=m.id 
                                JOIN tb_tingkat_pendidikan AS t ON m.tingkat_pendidikan=t.id 
                                JOIN tb_paket AS pk ON j.paket_id=pk.id 
                                LEFT JOIN tb_history AS h ON h.detail_jadwal_id = dj.id 
                                WHERE 
                                    h.id IS NOT NULL AND 
                                    j.siswa_id = "'.$siswa->id.'"
                                ORDER BY tgl_pertemuan ASC, waktu_mulai ASC');
        if (count($model)>0){
            $data = array();
            $i = 0;
            foreach ($model as $row){
                $data[$i]['jadwal_id'] = $row->jadwal_id;
                $data[$i]['detail_jadwal_id'] = $row->id;
                $data[$i]['nama_pengajar'] = $row->fullname;
                $data[$i]['no_telp'] = $row->pengajar_cp;
                $data[$i]['photo'] = $row->photo;
                $data[$i]['label_mapel'] = $row->nama_mapel." - ".$row->nama_tingkat;
                $data[$i]['label_tanggal'] =  Jadwal::getLabelTanggal($row->tgl_pertemuan);
                $data[$i]['label_waktu'] = date("H:i", strtotime($row->waktu_mulai))." WITA";
                $data[$i]['label_tempat'] = $row->tempat;
                $data[$i]['pertemuan'] = $row->pertemuan;
                $data[$i]['keterangan'] = $row->history_keterangan;
                $data[$i]['tambahan_jam'] = $row->tambahan_jam;
                $data[$i]['durasi'] = $row->tambahan_jam;
                if($row->tambahan_jam>0){
                    $lebih = round($row->tambahan_jam/5, 0, PHP_ROUND_HALF_UP);
                    //$tarif = Tarif::getTarifByRate($rating);
                    $tarif = 3000;
                    $biaya = $tarif*$lebih;
                    $data[$i]['biaya'] = number_format($biaya, 0, ',', '.');
                }else{
                    $data[$i]['biaya'] = 0;
                }
                $i++;
            }
            return response()->json(['status'=>1,'data'=>$data]);
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function getJadwalLesSiswa(Request $request)
    {
        $user = User::find($request->input('user_id'));
        $siswa = $user->siswa;
        $model = DB::select('SELECT 
                                    dj.*,
                                    j.status,j.pengajar_id,j.siswa_id, 
                                    p.fullname,p.photo,p.pengajar_cp,  
                                    m.nama AS nama_mapel, 
                                    t.nama AS nama_tingkat, 
                                    h.id AS history_id 
                                FROM tb_detail_jadwal AS dj 
                                JOIN tb_jadwal AS j ON dj.jadwal_id=j.id 
                                JOIN tb_pengajar AS p ON dj.pengajar_id=p.id
                                JOIN tb_mapel AS m ON j.mapel_id=m.id 
                                JOIN tb_tingkat_pendidikan AS t ON m.tingkat_pendidikan=t.id 
                                LEFT JOIN tb_history AS h ON h.detail_jadwal_id = dj.id 
                                WHERE 
                                    h.id IS NULL AND 
                                    j.status = "1" AND 
                                    j.siswa_id = "'.$siswa->id.'" AND
                                    dj.tgl_pertemuan >= "'.date("Y-m-d").'"
                                ORDER BY tgl_pertemuan ASC, waktu_mulai ASC');
        if (count($model)>0){
            $data = array();
            $i = 0;
            foreach ($model as $row){
                $data[$i]['id'] = $row->id;
                $data[$i]['jadwal_id'] = $row->jadwal_id;
                $data[$i]['detail_jadwal_id'] = $row->id;
                $data[$i]['nama_pengajar'] = $row->fullname;
                $data[$i]['no_telp'] = $row->pengajar_cp;
                $data[$i]['photo'] = $row->photo;
                $data[$i]['label_mapel'] = $row->nama_mapel." - ".$row->nama_tingkat;
                $data[$i]['label_tanggal'] =  Jadwal::getLabelTanggal($row->tgl_pertemuan);
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

    public function rescheduleJadwal(Request $request)
    {
        $cek = \App\Models\Request::where("dt_jadwal_id",$request->input('dt_jadwal_id'))->where('type','RS')->count();
        if($cek>0){
            return response()->json(['status'=>0,'error'=>'Anda sudah mengajukan perubahan jadwal untuk pertemuan ini.']);
        }
        $user = User::find($request->input('user_id'));
        $type = $request->input('type');
        $detail_jadwal = DetailJadwal::find($request->input('dt_jadwal_id'));
        $jadwal = $detail_jadwal->jadwal;
        $mRequest = new \App\Models\Request();

        $mRequest->dt_jadwal_id = $request->input('dt_jadwal_id');
        $mRequest->siswa_id = $jadwal->siswa_id;
        $mRequest->pengajar_id = $jadwal->pengajar_id;
        $mRequest->request_by = $request->input('requested_by');
        $mRequest->type = $type;
        $mRequest->tgl_pertemuan = $request->input('tglPertemuan');
        $mRequest->jam_pertemuan = $request->input('waktuPertemuan');
        $mRequest->tempat = $request->input('tempatPertemuan');
        $mRequest->keterangan = $request->input('keterangan');
        $mRequest->status = "2";

        if($mRequest->request_by == 'SISWA'){
            $mRequest->request_id = $user->siswa->id;
        }else{
            $mRequest->request_id = $user->pengajar->id;
        }

        if($mRequest->save()){
            return response()->json(['status'=>1]);
        }
    }

    public function cancelJadwal(Request $request)
    {
        $cek = \App\Models\Request::where("dt_jadwal_id",$request->input('dt_jadwal_id'))->where('type','CC')->count();
        if($cek>0){
            return response()->json(['status'=>0,'error'=>'Anda sudah mengajukan pembatalan jadwal untuk pertemuan ini.']);
        }
        $user = User::find($request->input('user_id'));
        $type = $request->input('type');
        $detail_jadwal = DetailJadwal::find($request->input('dt_jadwal_id'));
        $jadwal = $detail_jadwal->jadwal;
        $mRequest = new \App\Models\Request();

        $mRequest->dt_jadwal_id = $request->input('dt_jadwal_id');
        $mRequest->siswa_id = $jadwal->siswa_id;
        $mRequest->pengajar_id = $jadwal->pengajar_id;
        $mRequest->request_by = $request->input('requested_by');
        $mRequest->type = $type;
        $mRequest->keterangan = $request->input('keterangan');
        $mRequest->status = "2";

        if($mRequest->request_by == 'SISWA'){
            $mRequest->request_id = $user->siswa->id;
        }else{
            $mRequest->request_id = $user->pengajar->id;
        }

        if($mRequest->save()){
            return response()->json(['status'=>1]);
        }
    }


}
