<?php

namespace App\Cron;

use App\Models\User;
use App\Models\UserQueue;
use App\Services\WeatherService\Properties\WeeklyService;
use App\Services\WeatherService\WeatherManager;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;

require_once(__DIR__ . '/../../bootstrap.php');

class FillQueue
{

    function __construct()
    {
        $weather_manager = new WeatherManager();
        $template = new WeeklyService();
        $users = User::all();
        DB::statement('SET NAMES utf8mb4');
        foreach ($users as $user) {
            if ($user->userCity[0]->city['city_id']) {
                $weather_data = $weather_manager->getWeeklyWeather($user->userCity[0]->city['city_id'], $user->lang, Carbon::now()->startOfDay());
                $template = $template->getTemplate($user->userCity[0]->city['title_ua'],
                    $weather_data, $user->lang,
                    Carbon::now()->startOfDay()->timestamp,
                    Carbon::now()->endOfDay()->timestamp);
                UserQueue::create([
                    'chat_id' => $user->chat_id,
                    'message' => $template
                ]);
            }
        }
    }

}

new FillQueue();