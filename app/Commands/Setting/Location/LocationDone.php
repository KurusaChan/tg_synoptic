<?php

namespace App\Commands\Setting\Location;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\City;
use App\Models\UserCity;
use App\Services\Status\UserStatusService;

class LocationDone extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::SETTINGS_LOCATION_SELECTING) {
            $exploded = explode(',', $this->update->getMessage()->getText());
            if ($exploded[0]) {
                $search_by_string = $exploded[0];
            } else {
                $search_by_string = $this->update->getMessage()->getText();
            }

            $possible_city = City::where('title_' . strtolower($this->user->lang), $search_by_string)->get();
            if ($possible_city->count()) {
                $update = UserCity::where('user_id', $this->user->id)->where('city_id', null)->update([
                    'city_id' => $possible_city[0]->id
                ]);
                if (!$update) {
                    UserCity::create([
                        'user_id' => $this->user->id,
                        'district_id' => $possible_city[0]->district->id,
                        'city_id' => $possible_city[0]->id,
                    ]);
                }
                
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['saved_your_city']);
                $this->triggerCommand(MainMenu::class);
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
            }
        }
    }
}