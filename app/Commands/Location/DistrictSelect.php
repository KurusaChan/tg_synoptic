<?php

namespace App\Commands\Location;

use App\Commands\BaseCommand;
use App\Models\UserCity;
use App\Services\LocationSearch\DistrictList;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class DistrictSelect extends BaseCommand
{

    function processCommand($param = null)
    {
        $search = new DistrictList();
        $search->setLang($this->user->lang);

        if ($this->user->status === UserStatusService::DISTRICT_SELECT) {
            $search->setTitle($this->update->getMessage()->getText());
            $district_id = $search->searchDistrict();
            if ($district_id) {
                UserCity::create([
                    'user_id' => $this->user->id,
                    'district_id' => $district_id
                ]);
                $this->triggerCommand(CitySelect::class);
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['cantFindCity']);
            }
        } else {
            $buttons = $search->getDistrictButtons();
            $buttons[] = [$this->text['back']];

            $this->user->status = UserStatusService::DISTRICT_SELECT;
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['selectDistrict'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}