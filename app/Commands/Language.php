<?php

namespace App\Commands;

use App\Commands\Location\SelectLocationType;
use App\Services\Language\ChangeLanguageService;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Language extends BaseCommand
{

    function processCommand($param = null)
    {
        if ($this->user->status === UserStatusService::SET_START_LANGUAGE) {
            if (isset(ChangeLanguageService::$locales[$this->update->getMessage()->getText()])) {
                $this->user->lang = ChangeLanguageService::$locales[$this->update->getMessage()->getText()];
                $this->user->save();
                $this->triggerCommand(SelectLocationType::class);
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['select_language_from_buttons']);
            }
        } elseif ($this->user->status === UserStatusService::NEW) {
            $this->user->status = UserStatusService::SET_START_LANGUAGE;
            $this->user->save();

            $this->getBot()->sendMessageWithKeyboard($this->bot_user->getId(), '
🇺🇦 Виберіть мову з клавіатури нижче
🇷🇺 Выберите язык с клавиатуры ниже
🇺🇸 Select language the from keyboard', new ReplyKeyboardMarkup([
                [ChangeLanguageService::LANG_TEXT_UA],
                [ChangeLanguageService::LANG_TEXT_RU],
                [ChangeLanguageService::LANG_TEXT_EN]
            ], false, true));
        }

    }

}