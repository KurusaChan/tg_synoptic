<?php

namespace App\Commands\Location;

use App\Commands\BaseCommand;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CityName extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::CITY_NAME) {
            if (strlen(trim($this->update->getMessage()->getText())) > 3) {
                $search = new \App\Services\LocationSearch\CityName();
                $search->setLang($this->user->lang);
                $search->setTitle($this->update->getMessage()->getText());
                $buttons = $search->getCityButtons();

                $this->user->status = UserStatusService::LOCATION_SELECTING;
                $this->user->save();

                if ($buttons) {
                    $buttons[] = [$this->text['back']];
                    $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['didYouMeanThisCity'], new ReplyKeyboardMarkup($buttons, false, true));
                } else {
                    $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
                }
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['moreSymbols']);
            }
        } else {
            $this->user->status = UserStatusService::CITY_NAME;
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['requestToWriteCity'], new ReplyKeyboardMarkup([
                [$this->text['back']]
            ], false, true));
        }
    }

}