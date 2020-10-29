<?php

namespace App\Commands\Weather;

use App\Commands\BaseCommand;
use App\Models\City;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SelectWeatherCity extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::FORECAST_CITY_SELECT || $this->user->status === UserStatusService::CURRENT_CITY_SELECT) {
            // если пользователь выбрал город со списка, а не отправил локацию
            if ($this->update->getMessage()->getText()) {
                // поскольку сообщение вида "НАЗВАНИЕ_ГОРОДА, ОБЛАСТЬ", разбиваем по запятой
                $exploded = explode(',', $this->update->getMessage()->getText());
                if ($exploded[0]) {
                    $search_by_string = $exploded[0];
                } else {
                    $search_by_string = $this->update->getMessage()->getText();
                }

                // проверяем есть ли такой город в списке городов пользователя
                $possible_city = City::where('title_' . strtolower($this->user->lang), $search_by_string)->get();
                if ($possible_city->count()) {
                    $city_id = $possible_city[0]->id;
                    if ($this->user->status === UserStatusService::FORECAST_CITY_SELECT) {
                        $this->triggerCommand(WeatherLess::class, $city_id);
                    } else {
                        $this->triggerCommand(CurrentWeatherLess::class, $city_id);
                    }
                } else {
                    $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
                }
            } elseif ($this->update->getMessage()->getLocation()) {
                // если пользователь отправил локацию
                $search = new \App\Services\LocationSearch\TgLocation();
                $search->setLang($this->user->lang);
                $search->setLocation($this->update->getMessage()->getLocation());

                // спрашиваем верный ли город
                $buttons = $search->getCityButtons();
                if ($buttons) {
                    $buttons[] = $this->text['back'];
                    $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['didYouMeanThisCity'], new ReplyKeyboardMarkup([$buttons], false, true));
                } else {
                    $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
                }
            }
        } else {
            // если у пользователя есть выбранные города - отобразить список для выбора
            if (count($this->user->userCity) > 0) {
                $this->user->status = $this->update->getMessage()->getText() == $this->text['current_weather'] ? UserStatusService::CURRENT_CITY_SELECT : UserStatusService::FORECAST_CITY_SELECT;
                $this->user->save();

                foreach ($this->user->userCity as $city) {
                    $city_list[] = [$city->city->{'title_' . strtolower($this->user->lang)} . ', ' . $city->city->district->{'title_' . strtolower($this->user->lang)} . $this->text['district']];
                }
            }

            // добавление кнопки "отправить локацию"
            $city_list[] = [['text' => $this->text['sendLocationType'], 'request_location' => true]];
            $city_list[] = [$this->text['back']];

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['selectCityFromList'], new ReplyKeyboardMarkup($city_list, false, true));
        }
    }

}