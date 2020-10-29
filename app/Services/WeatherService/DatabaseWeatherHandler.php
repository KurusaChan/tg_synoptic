<?php

namespace App\Services\WeatherService;

use App\Models\WeatherCache;
use Carbon\Carbon;

class DatabaseWeatherHandler
{

    const CACHE_TIME = [
        WeatherManager::WEATHER_MODE_WEEKLY => 90,
        WeatherManager::WEATHER_MODE_COO => 90,
        WeatherManager::WEATHER_MODE_CURRENT => 10,
        WeatherManager::WEATHER_MODE_INLINE_QUERY => 10,
    ];

    const OWM_FUNCTION = [
        WeatherManager::WEATHER_MODE_COO => 'WEATHER',
        WeatherManager::WEATHER_MODE_WEEKLY => 'FORECAST',
        WeatherManager::WEATHER_MODE_CURRENT => 'WEATHER',
        WeatherManager::WEATHER_MODE_INLINE_QUERY => 'WEATHER'
    ];

    /**
     * @param $city_id
     * @param string $mode
     * @param string $lang
     * @param int $date
     * @return array
     */
    public function getWeather($city_id, string $mode, string $lang, $date = null)
    {
        $date = $date ?: Carbon::today();
        $owm_api = new OwmApiService();

        if ($mode == WeatherManager::WEATHER_MODE_COO) {
            return $owm_api->call(strtolower(static::OWM_FUNCTION[$mode]), [
                'lat' => $city_id['latitude'],
                'lon' => $city_id['longitude']
            ]);
        }

        $weather_data = WeatherCache::where('city_id', $city_id)->whereDate('created_at', Carbon::today())->where('for_date', $date)->where('status', $mode)->get();
        if (!$weather_data->count() || !$this->isValidData($weather_data[0]['created_at'], static::CACHE_TIME[$mode])) {
            $owm_data = $owm_api->call(static::OWM_FUNCTION[$mode], [
                'id' => $city_id,
                'lang' => $lang
            ]);
            if ($mode == WeatherManager::WEATHER_MODE_INLINE_QUERY) {
                WeatherCache::create([
                    'city_id' => $city_id,
                    'status' => $mode,
                    'owm_callback' => json_encode($owm_data, true),
                    'sinoptik_callback' => null,
                    'for_date' => $date ?: Carbon::today()
                ]);
            } else {
                WeatherCache::create([
                    'city_id' => $city_id,
                    'status' => $mode,
                    'owm_callback' => json_encode($owm_data, true),
                    'sinoptik_callback' => json_encode(ParserSinoptik::parse($city_id, $date->timestamp ?: time()), true),
                    'for_date' => $date ?: Carbon::today()
                ]);
            }
            $weather_data = WeatherCache::where('city_id', $city_id)->whereDate('created_at', Carbon::today())->where('for_date', $date)->where('status', $mode)->get();
        }
        return $weather_data[0];
    }


    protected function isValidData($created_at, int $cache_time): bool
    {
        if ($created_at) {
            $minutes_left = Carbon::now()->diffInMinutes($created_at);
            return $minutes_left < $cache_time;
        }

        return false;
    }

}
