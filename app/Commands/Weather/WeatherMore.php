<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\City;
use App\Services\Status\UserStatusService;
use App\Services\WeatherService\ParserSinoptik;
use App\Services\WeatherService\WeatherManager;
use Carbon\Carbon;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WeatherMore extends BaseCommand
{

    function processCommand($param = null)
    {
        $city_id = json_decode($this->update->getCallbackQuery()->getData(), true)['id'];
        $callback_data = $this->update->getCallbackQuery() ? json_decode($this->update->getCallbackQuery()->getData(), true) : null;
        if ($callback_data && $callback_data['day']) {
            $next_day = $callback_data['day'];
        } else {
            if (date('H') > 12) {
                $next_day = 1;
            } else {
                $next_day = 0;
            }
        }
        
        $text = $this->getWeatherMessage($city_id, $next_day);
        if ($text) {
            $buttons = [];

            if ($next_day === 0) {
                $arrow[] = [
                    'text' => $this->text['next'],
                    'callback_data' => json_encode([
                        'a' => 'weather_next_less',
                        'day' => $next_day + 1,
                        'id' => $city_id,
                    ])
                ];
            } elseif ($next_day < 4 || $next_day === 1) {
                $arrow[] = [
                    'text' => $this->text['prev'],
                    'callback_data' => json_encode([
                        'a' => 'weather_prev_less',
                        'day' => $next_day - 1,
                        'id' => $city_id,
                    ])
                ];
                $arrow[] = [
                    'text' => $this->text['next'],
                    'callback_data' => json_encode([
                        'a' => 'weather_next_less',
                        'day' => $next_day + 1,
                        'id' => $city_id,
                    ])
                ];
            } elseif ($next_day == 4) {
                $arrow[] = [
                    'text' => $this->text['prev'],
                    'callback_data' => json_encode([
                        'a' => 'weather_prev_more',
                        'day' => $next_day - 1,
                        'id' => $city_id,
                    ])
                ];
            }

            $buttons[] = [[
                'text' => $this->text['lessInfo'],
                'callback_data' => json_encode([
                    'a' => 'weather_less',
                    'id' => $city_id,
                    'day' => $next_day
                ])
            ]];
            $buttons[] = $arrow;

        $this->getBot()->editMessageText($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), $text, 'html', true, new InlineKeyboardMarkup($buttons));
        }
    }

    /**
     * @param int $city_id
     * @param int $day
     * @return mixed
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getWeatherMessage(int $city_id, $day = 0)
    {
        $city = \App\Models\City::where('id', $city_id)->get();

        $weather_manager = new WeatherManager();
        $weather_data = $weather_manager->getWeeklyWeather($city[0]->city_id, $this->user->lang, Carbon::now()->addDays($day)->startOfDay());

        $template = new \App\Services\WeatherService\Properties\WeeklyService();
        return $template->getTemplate($city[0]->title_ua, $weather_data, $this->user->lang, Carbon::now()->addDays($day)->startOfDay()->timestamp, Carbon::now()->addDays($day)->endOfDay()->timestamp, true);
    }

}
