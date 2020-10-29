<?php
return [
    'weather_more' => \App\Commands\Weather\WeatherMore::class,
    'weather_less' => \App\Commands\Weather\WeatherLess::class,
    'weather_next_less' => \App\Commands\Weather\WeatherLess::class,
    'weather_prev_less' => \App\Commands\Weather\WeatherLess::class,
    'weather_next_more' => \App\Commands\Weather\WeatherMore::class,
    'weather_prev_more' => \App\Commands\Weather\WeatherLess::class,
    'generate_current_image' => \App\Commands\Weather\GenerateImage::class,
];