<?php

namespace App\Services\WeatherService;

class WeatherManager {

    const WEATHER_MODE_WEEKLY = 'WEEKLY';
    const WEATHER_MODE_CURRENT = 'CURRENT';
    const WEATHER_MODE_COO = 'COO';
    const WEATHER_MODE_INLINE_QUERY = 'WEATHER_MODE_INLINE_QUERY';

    public $databaseWeatherHandler;

    public function __construct()
    {
        $this->databaseWeatherHandler = new DatabaseWeatherHandler();
    }

    /**
     * @param int $city_id
     * @param string $lang
     * @param $date
     * @return array
     */
    public function getWeeklyWeather(int $city_id, string $lang, $date)
    {
        return $this->databaseWeatherHandler->getWeather($city_id, static::WEATHER_MODE_WEEKLY, $lang, $date);
    }

    /**
     * @param $coo
     * @param string $lang
     * @return array
     */
    public function getCooWeather($coo, string $lang)
    {
        return $this->databaseWeatherHandler->getWeather($coo, static::WEATHER_MODE_COO, $lang);
    }

    /**
     * @param int $city_id
     * @param $lang
     * @return array
     */
    public function getCurrentWeather(int $city_id, $lang)
    {
        return $this->databaseWeatherHandler->getWeather($city_id, static::WEATHER_MODE_CURRENT, $lang);
    }

    /**
     * @param int $city_id
     * @param $lang
     * @return array
     */
    public function getInlineQueryWeather(int $city_id, $lang)
    {
        return $this->databaseWeatherHandler->getWeather($city_id, static::WEATHER_MODE_CURRENT, $lang);
    }

}
