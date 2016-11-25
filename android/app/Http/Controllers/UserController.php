<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Mapel;
use App\Models\MapelCalonPengajar;
use App\Models\MapelPengajar;
use App\Models\Notif;
use App\Models\Pengajar;
use App\Models\PrestasiPengajar;
use App\Models\Siswa;
use App\Models\TingkatPendidikan;
use App\Models\TingkatPendidikanCalonPengajar;
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
        $type = $request->input('type');

        $user = User::where('email',$email)
            ->where('password',md5($password))
            ->where('type',$type)
            //->where('status',1)
            ->first();
        if($user && count($user)>0){
            if($user->type == User::SISWA){
                if($user->status==0){
                    return response()->json(['status'=>0,'error'=>'Login gagal. Account anda belum aktif.']);
                }
                $siswa = $user->siswa;
                return response()->json(['status'=>1,'data'=>$siswa]);
            }
            if($user->type == User::PENGAJAR){
                if($user->status==0){
                    return response()->json(['status'=>0,'error'=>'Login gagal. Account anda belum aktif atau belum lolos seleksi.']);
                }
                $pengajar = $user->pengajar;
                return response()->json(['status'=>1,'data'=>$pengajar]);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Login gagal. Email atau Password salah.']);
        }
    }

    public function registerPengajar(Request $request)
    {
        $user = User::where('email',$request->input('email'))->count();
        $pengajar = Pengajar::where('pengajar_cp',$request->input('telp'))->count();

        if($user == 0 && $pengajar == 0){
            $alamat = $this->geocoding_alamat($request->input('alamat').", ".$request->input('kodepos'));
            if($alamat['status']=="OK"){

                $latitude = $alamat['results'][0]['geometry']['location']['lat'];
                $longitude = $alamat['results'][0]['geometry']['location']['lng'];

                //$cabang = Cabang::where('nama',$request->input('zona'))->first();
                $model = new User();
                $mPengajar = new Pengajar();

                $model->email = $request->input('email');
                $model->password = md5($request->input('password'));
                $model->status = 3;
                $model->type = User::PENGAJAR;
                $model->token = md5($request->input('email'));
                if($model->save()){
                    $mPengajar->user_id = $model->id;
                    //$mPengajar->zona_id = $cabang->id;
                    $mPengajar->fullname = ucfirst($request->input('nama'));
                    $mPengajar->pengajar_alamat = $request->input('alamat');
                    $mPengajar->kodepos = $request->input('kodepos');
                    $mPengajar->latitude = $latitude;
                    $mPengajar->longitude = $longitude;
                    $mPengajar->pengajar_cp = $request->input('telp');
                    $mPengajar->pengajar_pendidikan = $request->input('edukasi');
                    $mPengajar->status_mengajar = Pengajar::AVALAIBLE;
                    if($mPengajar->save()){
                        if($request->input('jumlah_prestasi')>0){
                            for($i=1; $i<=$request->input('jumlah_prestasi'); $i++){
                                $prestasi = new PrestasiPengajar();
                                $prestasi->pengajar_id = $mPengajar->id;
                                $prestasi->prestasi = $request->input('prestasi'.$i);
                                $prestasi->save();
                            }
                        }
                        if($request->input('jumlah_mapel')>0){
                            for($i=1; $i<=$request->input('jumlah_mapel'); $i++){
                                $mapel = new MapelCalonPengajar();
                                $mapel->pengajar_id = $mPengajar->id;
                                $mapel->mapel = $request->input('mapel'.$i);
                                $mapel->save();
                            }
                        }
                        if($request->input('jumlah_pendidikan')>0){
                            for($i=1; $i<=$request->input('jumlah_pendidikan'); $i++){
                                $tingkatpendidikan = new TingkatPendidikanCalonPengajar();
                                $tingkatpendidikan->pengajar_id = $mPengajar->id;
                                $tingkatpendidikan->tingkat_pendidikan = $request->input('pendidikan'.$i);
                                $tingkatpendidikan->save();
                            }
                        }
                        //$msg = 'Klik <a href="'.route('activation',['token'=>$model->token]).'">disini</a> atau tautan berikut untuk verifikasi email anda: \n'.route('activation',['token'=>$model->token]);
                        //$msg = wordwrap($msg,70);
                        //mail($request->input('email'),"Ganda Edukasi - Account Activation",$msg);
                        $to = $request->input('email');
                        $subject = 'Edukezy - Account Activation';
                        $from = 'info@edukezy.com/';

                        $headers  = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= 'From: '.$from."\r\n".
                            'Reply-To: '.$from."\r\n" .
                            'X-Mailer: PHP/' . phpversion();

                        $message = '<html><body>';
                        $message .= '<h3 style="color:#000000;">Hi '.$mPengajar->fullname.'!</h3>';
                        $message .= '<p style="color:#000000;font-size:18px;">Klik <a href="'.route('activation',['token'=>$model->token]).'">disini</a> atau tautan berikut untuk verifikasi email anda: '.route('activation',['token'=>$model->token]).'</p>';
                        $message .= '</body></html>';

                        mail($to, $subject, $message, $headers);
                        return response()->json(['status'=>1]);
                    }
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Gagal registrasi. Alamat anda tidak ditemukan.']);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Gagal registrasi. Email atau No. Telp anda sudah digunakan.']);
        }

    }

    public function registerSiswa(Request $request)
    {
        $user = User::where('email',$request->input('email'))->count();
        $siswa = Siswa::where('siswa_cp',$request->input('telp'))->count();
        if($user == 0 && $siswa == 0){
            //cek lat & long siswa
            $alamat = $this->geocoding_alamat($request->input('alamat').", ".$request->input('kodepos'));
            if($alamat['status']=="OK"){
                $latitude = $alamat['results'][0]['geometry']['location']['lat'];
                $longitude = $alamat['results'][0]['geometry']['location']['lng'];
                $cabang = $this->cariCabangTerdekat($latitude,$longitude);
                if($cabang==null){
                    return response()->json(['status'=>0,'error'=>'Tidak ada cabang yang tersedia unutk anda.']);
                }else{
                    $tingkat = TingkatPendidikan::where('nama',$request->input('tingkat'))->first();
                    $model = new User();
                    $mSiswa = new Siswa();

                    $model->email = $request->input('email');
                    $model->password = md5($request->input('password'));
                    $model->status = 0;
                    $model->type = User::SISWA;
                    $model->token = md5($request->input('email'));

                    if($model->save()){
                        $mSiswa->user_id = $model->id;
                        $mSiswa->zona_id = $cabang->id;
                        $mSiswa->latitude = $latitude;
                        $mSiswa->longitude = $longitude;
                        $mSiswa->fullname = ucfirst($request->input('nama'));
                        $mSiswa->alamat = $request->input('alamat');
                        $mSiswa->kodepos = $request->input('kodepos');
                        $mSiswa->tempat_lahir = ucfirst($request->input('tempat_lahir'));
                        $mSiswa->tgl_lahir = $request->input('tgl_lahir');
                        $mSiswa->siswa_cp = $request->input('telp');
                        $mSiswa->siswa_wali = ucfirst($request->input('wali'));
                        $mSiswa->siswa_pendidikan = $tingkat->id;
                        if($mSiswa->save()){
                            //$msg = "Klik tautan berikut untuk mengaktifkan account anda: \n".route('activation',['token'=>$model->token]);
                            //$msg = wordwrap($msg,70);
                            //mail($request->input('email'),"Ganda Edukasi - Account Activation",$msg);

                            $to = $request->input('email');
                            $subject = 'Edukezy - Account Activation';
                            $from = 'info@edukezy.com/';

                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            $headers .= 'From: '.$from."\r\n".
                                'Reply-To: '.$from."\r\n" .
                                'X-Mailer: PHP/' . phpversion();

                            $message = '<html><body>';
                            $message .= '<h3 style="color:#000000;">Hi '.$mSiswa->fullname.'!</h3>';
                            $message .= '<p style="color:#000000;font-size:18px;">Klik <a href="'.route('activation',['token'=>$model->token]).'">disini</a> atau tautan berikut untuk verifikasi email anda: '.route('activation',['token'=>$model->token]).'</p>';
                            $message .= '</body></html>';

                            mail($to, $subject, $message, $headers);

                            return response()->json(['status'=>1]);
                        }
                    }
                }
            }else{
                return response()->json(['status'=>0,'error'=>'Alamat anda tidak ditemukan.']);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Email atau No. Telp telah digunakan.']);
        }
    }
    private function geocoding_alamat($alamat)
    {
        $key = env("GEOCODE_KEY");
        $alamat = str_replace(' ', '+', $alamat);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$alamat&region=id&key=$key";
        return json_decode(file_get_contents($url),true);
    }

    private function cariCabangTerdekat($lat, $lng)
    {
        $key = env("DISTANCE_MATRIX_KEY");
        $cabang = Cabang::all();
        $destination = "";
        foreach ($cabang as $row){
            $destination.=$row->latitude.",".$row->logitude."|";
        }
        $destination = substr($destination, 0,-1);
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$lat,$lng&destinations=$destination&mode=driving&language=id&key=$key";
        $result = json_decode(file_get_contents($url),true);
        if($result['status']=="OK"){
            $elements =  $result['rows'][0]['elements'];
            $min = 9999999999;
            $value = array();
            foreach ($elements as $key=>$row){
                if($row['status']=="OK"){
                    if($row['distance']['value']<$min){
                        $value = $cabang[$key];
                        $min = $row['distance']['value'];
                    }
                }
            }
            return $value;
        }else{
            return null;
        }
    }

    public function cekProfilePengajar(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($user && count($user)>0){
            $pengajar = $user->pengajar;
            $isNewAccount = 0;
            if($pengajar->kodepos == null){
                return response()->json(['status'=>2,'isNewAccount'=>$isNewAccount]);
            }
            $tingkat_pengajar = TingkatPendidikanPengajar::where('pengajar_id',$pengajar->id)->count();
            $mapel_pengajar = MapelPengajar::where('pengajar_id',$pengajar->id)->count();
            if($tingkat_pengajar == 0 || $mapel_pengajar == 0 || $pengajar->zona_id == null){
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
            $pengajar->zona_id = $request->input('zona_id');
            $pengajar->save();
            return response()->json(['status'=>1]);
        }else{
            return response()->json(['status'=>0]);
        }

    }

    public function activation($token)
    {
        $user = User::where('token',$token)->first();
        if($user && count($user)>0){
            if($user->type == User::PENGAJAR){
                $user->status = 0;
                $message = "<h3>Email anda telah diverifikasi. Mohon tunggu konfirmasi dari support kami untuk meninjau account anda.</h3>";
                return '<meta http-equiv="refresh" content="0; url=https://edukezy.com/verifikasi.php" />';
            }else{
                $user->status = 1;
                $message = "<h3>Account anda telah aktif. Silahkan login melalui aplikasi.</h3>";
            }
            if($user->type == User::PENGAJAR && $user->status == 0){
                $user->status = 0;
                $message = "<h3>Email anda telah diverifikasi. Mohon tunggu konfirmasi dari support kami untuk meninjau account anda.</h3>";
            }
            $user->token = null;
            if($user->save()){
                return $message;
            }
        }else{
            return "<h3>Email anda telah diverifikasi. Mohon tunggu konfirmasi dari support kami untuk meninjau account anda.</h3>";
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
                $tingkat = TingkatPendidikan::find($siswa->siswa_pendidikan);
                $siswa['tingkat_pendidikan'] = $tingkat->nama;
                $cabang = Cabang::find($siswa->zona_id);
                $siswa['cabang'] = $cabang->nama;
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
            if ($request->hasFile('photo')) {
                $photoFolder = base_path('../images/photo/pengajar');
                $image = $request->file('photo');
                $ext = $image->getClientOriginalExtension();
                if($pengajar->photo != null){
                    if(is_file($photoFolder."/".$pengajar->photo)){
                        unlink($photoFolder."/".$pengajar->photo);
                    }
                }
                $filename = $pengajar->id.str_random(10).".".$ext;
                if($image->move($photoFolder, $filename)){
                    $pengajar->photo = $filename;
                }
            }
            $pengajar->fullname = $request->input('name');
            $pengajar->pengajar_cp = $request->input('phone');
            $pengajar->pengajar_pendidikan = $request->input('pendidikan');
            $pengajar->pengajar_alamat = $request->input('alamat');
            $user->email = $request->input('email');
            if($pengajar->save()){
                $user->save();
                return response()->json(['status'=>1]);
            }
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function editProfileSiswa(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        if($user_id && count($user)){
            $siswa = $user->siswa;
            if ($request->hasFile('photo')) {
                $photoFolder = base_path('../images/photo/siswa');
                $image = $request->file('photo');
                $ext = $image->getClientOriginalExtension();
                if($siswa->photo != null){
                    if(is_file($photoFolder."/".$siswa->photo)){
                        unlink($photoFolder."/".$siswa->photo);
                    }
                }
                $filename = $siswa->id.str_random(10).".".$ext;
                if($image->move($photoFolder, $filename)){
                    $siswa->photo = $filename;
                }
            }
            $siswa->fullname = $request->input('nama');
            $siswa->alamat = $request->input('alamat');
            $siswa->tempat_lahir = $request->input('tempat_lahir');
            $siswa->tgl_lahir = $request->input('tgl_lahir');
            $siswa->siswa_cp = $request->input('phone');
            $siswa->siswa_wali = $request->input('wali');
            $user->email = $request->input('email');
            if($siswa->save()){
                $user->save();
                return response()->json(['status'=>1]);
            }
        }else{
            return response()->json(['status'=>0]);
        }
    }

    public function createNotif(Request $request)
    {
        $notif = Notif::where('user_id',$request->input('user_id'))->first();
        if(count($notif)>0){
            $notif->onesignal_id = $request->input('onesignal_id');
            $notif->save();
        }else{
            $model = new Notif();
            $model->user_id = $request->input('user_id');
            $model->onesignal_id = $request->input('onesignal_id');
            $model->save();
        }
    }

    public function deleteNotif(Request $request)
    {
        $notif = Notif::where('user_id',$request->input('user_id'))->first();
        if(count($notif)>0){
            $notif->delete();
        }
    }

    public function verifyAlamat(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $pengajar = $user->pengajar;

        $alamat = $this->geocoding_alamat($request->input('alamat').", ".$request->input('kodepos'));
        if($alamat['status']=="OK"){
            $latitude = $alamat['results'][0]['geometry']['location']['lat'];
            $longitude = $alamat['results'][0]['geometry']['location']['lng'];

            $pengajar->pengajar_alamat = $request->input('alamat');
            $pengajar->kodepos = $request->input('kodepos');
            $pengajar->latitude = $latitude;
            $pengajar->longitude = $longitude;

            if($pengajar->save()){
                return response()->json(['status'=>1]);
            }else{
                return response()->json(['status'=>0,'error'=>'Verifikasi alamat gagal.']);
            }
        }else{
            return response()->json(['status'=>0,'error'=>'Verifikasi alamat gagal. Alamat tidak ditemukan.']);
        }
    }

    //
}
