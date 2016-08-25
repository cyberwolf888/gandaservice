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

$app->post('/test', function(){

    $onesignal = new \App\Plugins\OneSignal();
    $onesignal->app_type = \App\Plugins\OneSignal::PENGAJAR;
    $onesignal->title = "Ganda Teacher";
    $onesignal->message = "Test notif pengajar hahahahaha";

    $response = $onesignal->sendMessageTo(['521333b8-9d0f-46c0-8b3d-3dba23d7cf98']);

    dd($response);
});
