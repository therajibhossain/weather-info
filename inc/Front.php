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
        $res = array();
        $loc = Config::visitor_location();
        if ($loc) {
            $res = $this->openweatherdata($loc['latitude'], $loc['longitude'], 'current');
        }
        return $res;
    }

    private function openweatherdata($lat, $lon, $part = '')
    {
        $result = get_transient('weather_info');
        if (false === $result) {
            $appId = "da52d59e7451b2345fb648365462a4ef";
            $url = "https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&units=metric&appid=$appId";
            if ($result = file_get_contents($url, false)) {
                $result = json_decode($result, true);
                set_transient('weather_info', $result, 1 * HOUR_IN_SECONDS);
            }

        }
        return $result;
    }
}