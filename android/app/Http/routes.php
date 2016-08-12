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

$app->post('/getMapelPelajar', ['as'=>'getMapelPelajar', 'uses' => 'MapelController@getMapelPelajar']);

$app->post('/getJadwalByMapel', ['as'=>'getJadwalByMapel', 'uses' => 'JadwalController@getJadwalByMapel']);