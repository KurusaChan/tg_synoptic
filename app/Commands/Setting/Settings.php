<?php

namespace App\Commands\Setting;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Settings extends BaseCommand
{

    function processCommand($param = null)
    {
        $this->user->status = UserStatusService::SETTINGS;
        $this->user->save();

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['settings'], new ReplyKeyboardMarkup([
            [/*$this->text['changeLang'],*/ $this->text['changeCity']],
            [$this->text['back']]
        ], false, true));
    }

}