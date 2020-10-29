<?php

namespace App\Commands;

use App\Commands\Location\SelectLocationType;
use App\Services\Status\UserStatusService;

class Start extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::NEW) {
            $this->triggerCommand(SelectLocationType::class);
        } else {
            $this->triggerCommand(MainMenu::class);
        }
    }

}

