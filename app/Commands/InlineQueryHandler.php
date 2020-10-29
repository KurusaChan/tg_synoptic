<?php

namespace App\Commands;

use App\Services\WeatherService\WeatherManager;
use TelegramBot\Api\Types\Inline\QueryResult\Article;

class InlineQueryHandler extends BaseCommand
{

    function processCommand($param = null)
    {
        $weather_manager = new WeatherManager();
        $search = new \App\Services\LocationSearch\CityName();
        $search->setLang($this->user->lang);
        $search->setTitle($this->update->getInlineQuery()->getQuery());

        $desc = '';
        $input_list = [];
        $reply_list = [];
        $city_list = array_slice($search->getCityButtons(), 0, 45);
        foreach ($city_list as $key => $city) {
            $city_name = explode(', ', $city[0])[0];
            $city_data = \App\Models\City::where('title_ua', $city_name)->get();

            if (sizeof($city_list) <= 5) {
                $weather_data = $weather_manager->getInlineQueryWeather($city_data[0]->city_id, $this->user->lang);
                $weather_data = json_decode($weather_data['owm_callback'], true);
                $icons = $this->getProperty($weather_data['weather'][0]['id'])['icon'];
                $rand_key = array_rand($icons, 1);
                $desc = ($icons[$rand_key] ?: '') . ' ' . round($weather_data['main']['temp']) . '° ' . $weather_data['weather'][0]['description'];
            }
            $input_list[] = $city[0];
            $reply_list[] = new Article(
                $key,
                $city[0],
                $desc
            );
        }

        $this->getBot()->answerInlineQuery($input_list, $this->update->getInlineQuery()->getId(), $reply_list, 1);
    }

    protected function getProperty(int $weatherId): array
    {
        $weatherProperties = include(__DIR__ . '/../config/weather_properties.php');
        return $weatherProperties[$weatherId];
    }

}