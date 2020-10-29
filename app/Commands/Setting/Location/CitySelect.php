<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Models\UserCity;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CitySelect extends BaseCommand
{

    function processCommand($param = null)
    {
        $undone_city = UserCity::where('user_id', $this->user->id)->where('city_id', null)->get();
        $city_list = City::where('district_id', $undone_city[0]['district_id'])->get();

        $data_list = [];
        foreach ($city_list as $city) {
            $data_list[] = [$city->{'title_' . strtolower($this->user->lang)}];
        }
        $data_list[] = [$this->text['back']];

        $this->user->status = UserStatusService::SETTINGS_LOCATION_SELECTING;
        $this->user->save();

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['selectCity'], new ReplyKeyboardMarkup($data_list, false, true));
    }

}