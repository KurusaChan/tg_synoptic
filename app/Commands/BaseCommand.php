<?php

namespace App\Commands;

use App\Models\Message;
use App\Models\User;
use App\Utils\Api;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

/**
 * Class BaseCommand
 * @package App\Commands
 */
abstract class BaseCommand
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \TelegramBot\Api\Types\User $user
     */
    protected $bot_user;

    protected $text;

    protected $update;

    /**
     * @var BotApi $bot
     */
    private $bot;

    public function __construct(Update $update)
    {
        $this->update = $update;
        if ($update->getCallbackQuery()) {
            $this->bot_user = $update->getCallbackQuery()->getFrom();
        } elseif ($update->getMessage()) {
            $this->bot_user = $update->getMessage()->getFrom();
        } elseif ($update->getInlineQuery()) {
            $this->bot_user = $update->getInlineQuery()->getFrom();
        } else {
            throw new \Exception('cant get telegram user data');
        }
    }

    /**
     * @param null $param
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\InvalidArgumentException
     */
    function handle($param = null)
    {
        $new_user = false;

        $this->user = User::where('chat_id', $this->bot_user->getId())->first();
        if (!$this->user) {
            $new_user = true;
            User::create([
                'chat_id' => $this->bot_user->getId(),
                'user_name' => $this->bot_user->getUsername(),
                'first_name' => $this->bot_user->getFirstName(),
            ]);
            $this->getBot()->sendMessage(375036391, '<b>Новий користувач:</b> @' . $this->bot_user->getUsername() . ', ' . $this->bot_user->getFirstName() ?: '', 'html');
            $this->user = User::where('chat_id', $this->bot_user->getId())->first();
        }

        $this->text = require(__DIR__ . '/../config/lang/' . strtolower($this->user['lang']) . '/bot.php');


        if ($this->update->getMessage()) {
            Message::create([
                'user_id' => $this->user->id,
                'text' => $this->update->getMessage()->getText()
            ]);
        }
        if ($new_user) {
            $this->triggerCommand(Start::class);
        } else {
            $this->processCommand($param);
        }
    }

    /**
     * @return Api
     */
    public function getBot(): Api
    {
        if (!$this->bot) {
            $this->bot = new Api(env('TELEGRAM_BOT_TOKEN'));
        }

        return $this->bot;
    }

    /**
     * @param $class
     * @param null $param
     */
    function triggerCommand($class, $param = null)
    {
        (new $class($this->update))->handle($param);
    }

    abstract function processCommand($param = null);

}