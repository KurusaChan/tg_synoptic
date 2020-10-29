<?php

namespace App\Commands\Location;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class TgLocation extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::LOCATION_WAITING) {
            $search = new \App\Services\LocationSearch\TgLocation();
            $search->setLang($this->user->lang);
            $search->setLocation($this->update->getMessage()->getLocation());
            $buttons = $search->getCityButtons();

            if ($buttons) {
                $this->user->status = UserStatusService::LOCATION_SELECTING;
                $this->user->save();
                $buttons[] = $this->text['back'];

                $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['didYouMeanThisCity'], new ReplyKeyboardMarkup([$buttons], false, true));
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
            }
        } else {
            $this->user->status = UserStatusService::LOCATION_WAITING;
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['sendYourLocation'], new ReplyKeyboardMarkup([
                [['text' => $this->text['click'], 'request_location' => true]],
                [$this->text['back']]
            ], false, true));
        }
    }
}