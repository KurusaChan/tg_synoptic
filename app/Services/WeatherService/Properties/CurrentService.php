<?php

namespace App\Services\WeatherService\Properties;

use App\Utils\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CurrentService
{

    /**
     * @param string $city_name
     * @param $weather_data
     * @param string $lang
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getTemplate(string $city_name, $weather_data, string $lang)
    {
        $result['city'] = $city_name;
        $result['date'] = date('h:i');

        $sinoptik_data = json_decode($weather_data['sinoptik_callback'], true);
        $weather_data = json_decode($weather_data['owm_callback'], true);

        $icons = $this->getProperty($weather_data['weather'][0]['id'])['icon'];
        $rand_key = array_rand($icons, 1);
        $result['weather_id'] = 200;
        $result['icon'] = $icons[$rand_key] ?: '';
        $result['temp'] = round($weather_data['main']['temp']);
        $result['desc'] = $weather_data['weather'][0]['description'];
        $result['wind_speed'] = $weather_data['wind']['speed'];
        $result['pressure'] = $weather_data['main']['pressure'];
        $result['humidity'] = $weather_data['main']['humidity'];
        $result['clouds'] = $weather_data['clouds']['all'];
        $result['detail'] = $sinoptik_data['detail'];
        $result['sunset'] = date('H:i', $weather_data['sys']['sunset']);
        $result['sunrise'] = date('H:i', $weather_data['sys']['sunrise']);

        $twig = Twig::getInstance();
        $template = $twig->load('current_' . strtolower($lang) . '.twig');
        $text = include(__DIR__ . '/../../../config/lang/' . strtolower($lang) . '/bot.php');

        return $template->render([
            'text' => $text,
            'weather' => $result,
        ]);
    }

    protected function getProperty(int $weatherId): array
    {
        $weatherProperties = include(__DIR__ . '/../../../config/weather_properties.php');
        return $weatherProperties[$weatherId];
    }

}