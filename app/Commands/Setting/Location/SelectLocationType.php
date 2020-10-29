<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Models\UserCity;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectLocationType extends BaseCommand
{

    function processCommand($param = null)
    {
        UserCity::where('user_id', $this->user->id)->where('city_id', null)->delete();

        if ($this->user->status === UserStatusService::SETTINGS_LOCATION_TYPE_SELECT) {
            switch ($this->update->getMessage()->getText()) {
                case $this->text['sendLocationType']:
                    $this->triggerCommand(TgLocation::class);
                    break;
                case $this->text['chooseFromList']:
                    $this->triggerCommand(DistrictSelect::class);
                    break;
                case $this->text['sendCityName']:
                    $this->triggerCommand(CityName::class);
                    break;
            }
        } else {
            $this->user->status = UserStatusService::SETTINGS_LOCATION_TYPE_SELECT;
            $this->user->save();

            $buttons[] = [$this->text['sendLocationType']];
            $buttons[] = [$this->text['chooseFromList']];
            $buttons[] = [$this->text['sendCityName']];
            $buttons[] = [$this->text['back']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['howChooseCityQuestion'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}