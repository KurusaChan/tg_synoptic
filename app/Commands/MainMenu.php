<?php

namespace App\Commands;

use App\Commands\Weather\WeatherLess;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class MainMenu extends BaseCommand
{

    function processCommand($text = false)
    {
        // HANDLING INLINE QUERY
        if ($this->user->status == UserStatusService::DONE) {
            $city_name = explode(', ', $this->update->getMessage()->getText())[0];
            $city_data = \App\Models\City::where('title_ua', $city_name)->first();
            if ($city_data) {
                $this->triggerCommand(WeatherLess::class, $city_data->id);
                exit();
            }
        }

        $this->user->status = UserStatusService::DONE;
        $this->user->save();

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['mainMenu'], new ReplyKeyboardMarkup([
            [$this->text['forecast']],
            [$this->text['current_weather']],
            [$this->text['feedback'], $this->text['changeCity']]
        ], false, true));
    }

}