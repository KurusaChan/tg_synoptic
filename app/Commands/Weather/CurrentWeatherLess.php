<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Services\WeatherService\WeatherManager;
use Carbon\Carbon;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CurrentWeatherLess extends BaseCommand
{

    function processCommand($param = null)
    {
        $city_id = $param;

        $text = $this->getWeatherMessage($city_id);
        if ($text) {
            $buttons = [
                [[
                    'text' => $this->text['generate_image'],
                    'callback_data' => json_encode([
                        'a' => 'generate_current_image',
                        'city_id' => $param
                    ])
                ]]
            ];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $text, new InlineKeyboardMarkup($buttons), $this->update->getMessage()->getMessageId());
        }
    }

    /**
     * @param int $city_id
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getWeatherMessage(int $city_id)
    {
        $city = \App\Models\City::where('id', $city_id)->get();
        $weather_manager = new WeatherManager();
        $weather_data = $weather_manager->getCurrentWeather($city[0]->city_id, $this->user->lang);

        $template = new \App\Services\WeatherService\Properties\CurrentService();
        return $template->getTemplate(
            $city[0]->{'title_' . strtolower($this->user->lang)},
            $weather_data,
            $this->user->lang
        );
    }

}
