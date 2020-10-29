<?php

use App\Services\Status\UserStatusService;

return [
    UserStatusService::NEW => \App\Commands\Setting\Location\TgLocation::class,
    UserStatusService::SETTINGS_LOCATION_WAITING => \App\Commands\Setting\Location\TgLocation::class,
    UserStatusService::SET_START_LANGUAGE => \App\Commands\Language::class,
    UserStatusService::SET_LANGUAGE => \App\Commands\Setting\Language::class,
    UserStatusService::LOCATION_TYPE_SELECT => \App\Commands\Location\SelectLocationType::class,
    UserStatusService::SETTINGS_LOCATION_TYPE_SELECT => \App\Commands\Setting\Location\SelectLocationType::class,
    UserStatusService::SETTINGS_DISTRICT_SELECT => \App\Commands\Setting\Location\DistrictSelect::class,
    UserStatusService::CITY_NAME => \App\Commands\Location\CityName::class,
    UserStatusService::LOCATION_WAITING => \App\Commands\Location\TgLocation::class,
    UserStatusService::LOCATION_SELECTING => \App\Commands\Location\LocationDone::class,
    UserStatusService::SETTINGS_LOCATION_SELECTING => \App\Commands\Setting\Location\LocationDone::class,
    UserStatusService::SETTINGS_CITY_NAME => \App\Commands\Setting\Location\CityName::class,
    UserStatusService::DISTRICT_SELECT => \App\Commands\Location\DistrictSelect::class,
    UserStatusService::FEEDBACK => \App\Commands\Feedback::class,
    UserStatusService::USER_CITY_LIST => \App\Commands\Setting\ViewCityList::class,
    UserStatusService::FORECAST_CITY_SELECT => \App\Commands\Weather\SelectWeatherCity::class,
    UserStatusService::CURRENT_CITY_SELECT => \App\Commands\Weather\SelectWeatherCity::class,
    UserStatusService::CITY_MENU => \App\Commands\Setting\CityMenu::class,
];