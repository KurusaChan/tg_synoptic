<?php
return [
    'back' => \App\Commands\Back::class,
    'feedback' => \App\Commands\Feedback::class,
    'settings' => \App\Commands\Setting\Settings::class,
    'changeLang' => \App\Commands\Setting\Language::class,
    'changeCity' => \App\Commands\Setting\CityMenu::class,
    'myCityList' => \App\Commands\Setting\ViewCityList::class,
    'forecast' => \App\Commands\Weather\SelectWeatherCity::class,
    'addCity' => \App\Commands\Setting\Location\SelectLocationType::class,
    'current_weather' => \App\Commands\Weather\SelectWeatherCity::class,
];