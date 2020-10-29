<?php

namespace App\Commands;

use App\Commands\Location\SelectLocationType;
use App\Commands\Setting\CityMenu;
use App\Commands\Setting\Settings;
use App\Services\Status\UserStatusService;

class Back extends BaseCommand
{

    function processCommand($param = null)
    {
        switch ($this->user->status) {
            case UserStatusService::SETTINGS_CITY_NAME:
            case UserStatusService::SETTINGS_DISTRICT_SELECT:
            case UserStatusService::SETTINGS_LOCATION_WAITING:
                $this->triggerCommand(\App\Commands\Setting\Location\SelectLocationType::class);
                break;
            case UserStatusService::USER_CITY_LIST:
            case UserStatusService::SETTINGS_LOCATION_TYPE_SELECT:
                $this->triggerCommand(CityMenu::class);
                break;
            case UserStatusService::CITY_MENU:
            case UserStatusService::SETTINGS_LOCATION_SELECTING:
            case UserStatusService::DONE:
            //case UserStatusService::SET_LANGUAGE:
                $this->triggerCommand(MainMenu::class);
                break;
            case UserStatusService::FEEDBACK:
            case UserStatusService::FORECAST_CITY_SELECT:
            case UserStatusService::SETTINGS:
            case UserStatusService::CURRENT_CITY_SELECT:
                $this->triggerCommand(MainMenu::class);
                break;
            case UserStatusService::CITY_NAME:
            case UserStatusService::LOCATION_WAITING:
            case UserStatusService::LOCATION_SELECTING:
            case UserStatusService::DISTRICT_SELECT:
                $this->triggerCommand(SelectLocationType::class);
                break;

        }
    }

}