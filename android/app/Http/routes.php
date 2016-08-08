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

$app->post('/complatingProfile', ['as'=>'compaltingprofile', 'uses' => 'UserController@complatingProfile']);

$app->get('/testmail', function (){
// the message
    $msg = "Klik tautan berikut untuk mengaktifkan account anda: \nhttp://gandaedukasi.esy.es/android/active/d76b4fe16e602bba95a7b43a838c37f1";
// use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);
// send email
    mail("wijaya.imd@gmail.com","Ganda Edukasi - Account Activation",$msg);
});
