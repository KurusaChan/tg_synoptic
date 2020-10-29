<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Services\WeatherService\WeatherManager;
use Carbon\Carbon;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class WeatherLess extends BaseCommand
{

    function processCommand($param = null)
    {
        $callback_data = null;
        $city_id = $param;

        // если пользователь жмакнул кнопку - вытягивать данные с нее
        if ($this->update->getCallbackQuery()) {
            $callback_data = json_decode($this->update->getCallbackQuery()->getData(), true);
            $next_day = $callback_data['day'];
            $city_id = $callback_data['id'];
        } else {
            // if its not too late - show today forecast, else - tommorow
            $next_day = date('H') > 12 ? 1 : 0;
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
                        'a' => 'weather_prev_less',
                        'day' => $next_day - 1,
                        'id' => $city_id,
                    ])
                ];
            }

            $buttons[] = [[
                'text' => $this->text['moreInfo'],
                'callback_data' => json_encode([
                    'a' => 'weather_more',
                    'id' => $city_id,
                    'day' => $next_day
                ])
            ]];
            $buttons[] = $arrow;

            if ($callback_data) {
                $this->getBot()->editMessageText($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), $text, 'html', true, new InlineKeyboardMarkup($buttons));
            } else {
                $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $text, new InlineKeyboardMarkup($buttons), $this->update->getMessage()->getMessageId());
            }
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
        return $template->getTemplate($city[0]->{'title_' . strtolower($this->user->lang)}, $weather_data, $this->user->lang, Carbon::now()->addDays($day)->startOfDay()->timestamp, Carbon::now()->addDays($day)->endOfDay()->timestamp);
    }

}
