<?php

namespace App\Commands\Setting;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Models\UserCity;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class ViewCityList extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::USER_CITY_LIST) {
            // if user still have more than one city
            if ($this->user->userCity->count() > 1) {
                // delete selected city
                $exploded = explode(',', $this->update->getMessage()->getText());
                $possible_city = City::where('title_' . strtolower($this->user->lang), $exploded[0])->get();
                if ($possible_city->count()) {
                    UserCity::where('user_id', $this->user->id)->where('city_id', $possible_city[0]['id'])->delete();
                }
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantDelete']);
            }
        } else {
            $this->user->status = UserStatusService::USER_CITY_LIST;
            $this->user->save();

            $city_list = [];
            foreach ($this->user->userCity as $city) {
                // title + district
                $city_list[] = [$city->city->{'title_' . strtolower($this->user->lang)} . ', ' . $city->city->district->{'title_' . strtolower($this->user->lang)} . $this->text['district']];
            }
            $city_list[] = [$this->text['back']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['myCityListInfo'], new ReplyKeyboardMarkup($city_list, false, true));
        }
    }

}