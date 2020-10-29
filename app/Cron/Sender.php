<?php

namespace App\Cron;

use App\Models\UserQueue;
use App\Utils\Api;

require_once(__DIR__ . '/../../bootstrap.php');

class Sender
{

    function __construct()
    {
        $bot = new Api(env('TELEGRAM_BOT_TOKEN'));
        $message_queue = UserQueue::where('status', 'NEW')->get();
        foreach ($message_queue as $message) {
            $sended_message = $bot->sendMessage($message->chat_id, $message->message);
            $message->status = 'SENDED';
            $message->message_id = $sended_message->getMessageId();
            $message->save();
        }
    }

}

new Sender();