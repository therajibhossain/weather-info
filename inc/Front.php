<?php


namespace WeatherInfo;


class Front
{
    public function __construct()
    {
        $this->index();
    }

    public function index()
    {
        $res = '';
        $loc = Config::visitor_location();
        if ($loc) {
            //$res = $this->openweatherdata($loc['latitude'], $loc['longitude'], 'current');
        }
        //echo '<pre>', print_r($res), '</pre>', exit();
    }

    private function openweatherdata($lat, $lon, $part = '')
    {
        $res = array();
        $appId = "da52d59e7451b2345fb648365462a4ef";
        echo $url = "https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=$part&appid=$appId";
        echo $url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&exclude=$part&appid=$appId";
        if ($res = file_get_contents($url, false)) {
            $res = json_decode($res, true);
        }
        return $res;
        //echo '<pre>', print_r($res), '</pre>', exit();
    }
}