<?php
namespace App\Plugins;
/**
 * Created by PhpStorm.
 * User: Karen
 * Date: 8/23/2016
 * Time: 7:45 PM
 */
class OneSignal
{
    const SISWA_APP_ID = "53e853d0-5379-46b7-9108-e8ac96fdf1a0";
    const SISWA_AUTH_KEY = "NWU5MDA2MmMtOTA5Zi00NGU4LWJjNWYtYjU5NzQ4N2ZjODcx";

    const PENGAJAR_APP_ID = "dee74357-44da-4c3c-935b-a297cf717fb1";
    const PENGAJAR_AUTH_KEY = "ZDVkMjlhYmItMTQ2Mi00MzM0LWI0ZDgtZWJiNmI2N2U4M2Jj";

    const SISWA = 1;
    const PENGAJAR = 2;

    public $title = "";
    public $message = "";
    public $app_type = "";
    public $url = "";

    public $small_icon = "logo";
    public $large_icon = "logo";
    public $android_sound = "notification";

    public function sendMessage(){
        if($this->app_type == OneSignal::SISWA){
            $app_id = OneSignal::SISWA_APP_ID;
            $auth_key = OneSignal::SISWA_AUTH_KEY;
        }else{
            $app_id = OneSignal::PENGAJAR_APP_ID;
            $auth_key = OneSignal::PENGAJAR_AUTH_KEY;
        }

        $content = array(
            "en" => $this->message
        );

        $heading = array(
            "en" => $this->title
        );

        $fields = array(
            'app_id' => $app_id,
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'headings' => $heading,
            'android_sound'=> $this->android_sound,
            'small_icon'=>$this->small_icon,
            'large_icon'=>$this->large_icon
        );

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

        return $this->getResponse($fields, $auth_key);
    }

    public function sendMessageTo($include_player_ids=array()){
        if($this->app_type == OneSignal::SISWA){
            $app_id = OneSignal::SISWA_APP_ID;
            $auth_key = OneSignal::SISWA_AUTH_KEY;
        }else{
            $app_id = OneSignal::PENGAJAR_APP_ID;
            $auth_key = OneSignal::PENGAJAR_AUTH_KEY;
        }

        $content = array(
            "en" => $this->message
        );

        $heading = array(
            "en" => $this->title
        );

        $fields = array(
            'app_id' => $app_id,
            'include_player_ids'=>$include_player_ids,
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'headings' => $heading,
            'android_sound'=> $this->android_sound,
            'small_icon'=>$this->small_icon,
            'large_icon'=>$this->large_icon
        );

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

        return $this->getResponse($fields, $auth_key);
    }

    public function sendMessageAdmin(){
        $app_id = "35aab1a1-3fdc-4696-9efe-1c151c5072f4";
        $auth_key = "ZWI4ZjhiZWQtMzFmYy00YTVhLWFiNmYtMDNkNjllODlkMzAx";

        $content = array(
            "en" => $this->message
        );

        $heading = array(
            "en" => $this->title
        );

        $fields = array(
            'app_id' => $app_id,
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'url' => $this->url,
            'contents' => $content,
            'headings' => $heading
        );

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

        return $this->getResponse($fields, $auth_key);
    }

    protected function getResponse($fields, $auth_key){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic '.$auth_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}