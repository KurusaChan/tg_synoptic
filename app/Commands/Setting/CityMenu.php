<?php

namespace App\Commands\Setting;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CityMenu extends BaseCommand
{

    function processCommand($param = null)
    {
        $this->user->status = UserStatusService::CITY_MENU;
        $this->user->save();

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['cityListInfo'], new ReplyKeyboardMarkup([
            [$this->text['addCity'], $this->text['myCityList']],
            [$this->text['back']]
        ], false, true));
    }

}