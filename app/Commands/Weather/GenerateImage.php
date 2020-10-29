<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Services\WeatherService\WeatherManager;

class GenerateImage extends BaseCommand
{

    function processCommand($param = null)
    {
        $city_id = json_decode($this->update->getCallbackQuery()->getData(), true)['city_id'];
        $city = \App\Models\City::where('id', $city_id)->get();
        $weather_manager = new WeatherManager();
        $weather_data = $weather_manager->getCurrentWeather($city[0]->city_id, $this->user->lang);

        $weather_data = json_decode($weather_data['owm_callback'], true);

        $image_maker = new \App\Services\ImageMakers\ImageMakerBlack();
        $image_maker->setDefaultFontfile($image_maker::MONTSERRAT_LIGHT);
        $image_maker->setImage();
        $image_maker->setWeatherData([
            'cityName' => $city[0]->{'title_' . strtolower($this->user->lang)},
            'weatherDesc' => $weather_data['weather'][0]['description'],
            'currentTemp' => round($weather_data['main']['temp']),
            'weatherId' => $weather_data['weather'][0]['id'],
            'sunrise' => $weather_data['sys']['sunrise'],
            'sunset' => $weather_data['sys']['sunset'],
            'windSpeed' => $weather_data['wind']['speed'],
            'clouds' => $weather_data['clouds']['all'],
        ]);
        $image_maker->constructImage();
        $this->getBot()->sendPhoto($this->user->chat_id, new \CURLFile($image_maker->getImage()), null, $this->update->getCallbackQuery()->getMessage()->getMessageId());
    }

}