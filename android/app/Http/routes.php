<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    //return $app->version();
});

$app->post('/login',['as' => 'login', 'uses' => 'UserController@login']);

$app->post('/register/pengajar',['as' => 'registerpengajar', 'uses' => 'UserController@registerPengajar']);

$app->post('/register/siswa',['as' => 'registersiswa', 'uses' => 'UserController@registerSiswa']);

$app->post('/article',['as' => 'article', 'uses' => 'ArticleController@showAll']);

$app->post('/getProfile/{type}', ['as' => 'get_profile', 'uses' => 'UserController@getProfle']);

$app->post('/cabang',['as' => 'cabang', 'uses' => 'CabangController@showAll']);

$app->get('/activation/{token}',['as'=>'activation', 'uses' => 'UserController@activation']);

$app->post('/tingkatpendidikan', ['as'=>'tingkatpendidikan', 'uses' => 'TingkatController@showAll']);

$app->post('/mapel', ['as'=>'mapel', 'uses' => 'MapelController@showAll']);

$app->post('/cekProfilePengajar', ['as'=>'cekprofilepengajar', 'uses' => 'UserController@cekProfilePengajar']);

$app->post('/editProfilePengajar', ['as'=>'editprofilepengajar', 'uses' => 'UserController@editprofilepengajar']);

$app->post('/editProfileSiswa', ['as'=>'editprofilepengajar', 'uses' => 'UserController@editProfileSiswa']);

$app->post('/complatingProfile', ['as'=>'compaltingprofile', 'uses' => 'UserController@complatingProfile']);

$app->post('/getMapelPengajar', ['as'=>'getMapelPengajar', 'uses' => 'MapelController@getMapelPengajar']);

$app->post('/tambahJadwalPengajar', ['as'=>'tambahJadwalPengajar', 'uses' => 'JadwalController@tambahJadwalPengajar']);

$app->post('/getJadwalPengajar', ['as'=>'getJadwalPengajar', 'uses' => 'JadwalController@getJadwalPengajar']);

$app->post('/deleteJadwalPengajar', ['as'=>'deleteJadwalPengajar', 'uses' => 'JadwalController@deleteJadwalPengajar']);

$app->post('/activeJadwalPengajar', ['as'=>'activeJadwalPengajar', 'uses' => 'JadwalController@activeJadwalPengajar']);

$app->post('/getMapelPelajar', ['as'=>'getMapelPelajar', 'uses' => 'MapelController@getMapelPelajar']);

$app->post('/getJadwalByMapel', ['as'=>'getJadwalByMapel', 'uses' => 'JadwalController@getJadwalByMapel']);

$app->post('/getPengajar', ['as'=>'getPengajar', 'uses' => 'JadwalController@getPengajar']);

$app->post('/getPaket', ['as'=>'getPaket', 'uses' => 'JadwalController@getPaket']);

$app->post('/buatJadwal', ['as'=>'buatJadwal', 'uses' => 'JadwalController@buatJadwal']);

$app->post('/getRequestSiswa', ['as'=>'getRequestSiswa', 'uses' => 'JadwalController@getRequestSiswa']);

$app->post('/getDetailRequestSiswa', ['as'=>'getDetailRequestSiswa', 'uses' => 'JadwalController@getDetailRequestSiswa']);

$app->post('/tolakJadwal', ['as'=>'tolakJadwal', 'uses' => 'JadwalController@tolakJadwal']);

$app->post('/terimaJadwal', ['as'=>'terimaJadwal', 'uses' => 'JadwalController@terimaJadwal']);

$app->post('/getJadwalForHistory', ['as'=>'getJadwalForHistory', 'uses' => 'JadwalController@getJadwalForHistory']);

$app->post('/createHistory', ['as'=>'createHistory', 'uses' => 'JadwalController@createHistory']);

$app->post('/getHistoryPengajar', ['as'=>'getHistoryPengajar', 'uses' => 'JadwalController@getHistoryPengajar']);

$app->post('/getJadwalLesPengajar', ['as'=>'getJadwalLesPengajar', 'uses' => 'JadwalController@getJadwalLesPengajar']);

$app->post('/getHistorySiswa', ['as'=>'getHistorySiswa', 'uses' => 'JadwalController@getHistorySiswa']);

$app->post('/getJadwalLesSiswa', ['as'=>'getJadwalLesSiswa', 'uses' => 'JadwalController@getJadwalLesSiswa']);

$app->post('/getProgramEdukasi', ['as'=>'getPrgramEdukasi', 'uses' => 'ProgramEdukasiController@showAll']);

$app->post('/cekRating', ['as'=>'cekRating', 'uses' => 'RatingController@cekRating']);

$app->post('/createRating', ['as'=>'createRating', 'uses' => 'RatingController@createRating']);

$app->post('/getPayment', ['as'=>'getPayment', 'uses' => 'PembayaranController@getPayment']);

$app->post('/cekBuktiPembayaran', ['as'=>'cekBuktiPembayaran', 'uses' => 'PembayaranController@cekBuktiPembayaran']);

$app->post('/createBuktiPembayaran', ['as'=>'createBuktiPembayaran', 'uses' => 'PembayaranController@createBuktiPembayaran']);

$app->post('/createNotif', ['as'=>'createNotif', 'uses' => 'UserController@createNotif']);

$app->post('/deleteNotif', ['as'=>'deleteNotif', 'uses' => 'UserController@deleteNotif']);

$app->post('/rescheduleJadwal', ['as'=>'rescheduleJadwal', 'uses' => 'JadwalController@rescheduleJadwal']);

$app->post('/cancelJadwal', ['as'=>'cancelJadwal', 'uses' => 'JadwalController@cancelJadwal']);

$app->get('/test', function(){
    return '<meta http-equiv="refresh" content="0; url=https://edukezy.com/verifikasi.php" />';
    //redirect("https://edukezy.com/verifikasi.php");
});

$app->get('/resend', function(){

    $to = "intenagung344@yahoo.co.id";
    $subject = 'Edukezy - Account Activation';
    $from = 'info@edukezy.com';

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $message = '<html><body>';
    $message .= '<h3 style="color:#000000;">Hi Anak Agung Inten Sakanti!</h3>';
    $message .= '<p style="color:#000000;font-size:18px;">Klik <a href="'.route('activation',['token'=>'fdf3ca9973f38d75b5947fc3474ec546']).'">disini</a> atau tautan berikut untuk verifikasi email anda: '.route('activation',['token'=>'fdf3ca9973f38d75b5947fc3474ec546']).'</p>';
    $message .= '</body></html>';

    //mail($to, $subject, $message, $headers);
});

$app->get('/migrasi', function(){
    $detail_jadwal = App\Models\DetailJadwal::all();
    foreach($detail_jadwal as $row){
        $pengajar_id = $row->jadwal->pengajar_id;
        $row->pengajar_id = $pengajar_id;
        $row->save();
    }
});
