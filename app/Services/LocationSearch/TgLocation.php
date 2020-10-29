<?php

namespace App\Services\LocationSearch;

use App\Models\City;
use App\Services\WeatherService\WeatherManager;
use TelegramBot\Api\Types\Location;

class TgLocation
{
    /**
     * @var Location
     */
    protected $location;
    protected $lang;

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @return array|null
     */
    public function getCityButtons()
    {
        $weather_manager = new WeatherManager();
        if ($this->location) {
            $weather_data = $weather_manager->getCooWeather(['longitude' => $this->location->getLongitude(), 'latitude' => $this->location->getLatitude()], $this->lang);

            if ($weather_data['id']) {
                $possible_city = City::where('city_id', $weather_data['id'])->get();

                return [$possible_city[0]->{'title_' . strtolower($this->lang)} . ', ' . $possible_city[0]->district->{'title_' . strtolower($this->lang)}];
            }
        }

        return null;
    }

}