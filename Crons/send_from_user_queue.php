<?php
use App\Services\Language\ChangeLanguageService;

require_once(SITE_ROOT . '/vendor/autoload.php');
$ua_text = include(SITE_ROOT . '/app/config/lang/ua/bot.php');
$ru_text = include(SITE_ROOT . '/app/config/lang/ru/bot.php');
$en_text = include(SITE_ROOT . '/app/config/lang/en/bot.php');

$db = \PHPtricks\Orm\Database::connect();
$tg = new \App\TgHelpers\TelegramApi();
//375036391
$queueList = $db->query('SELECT * FROM usersQueue LIMIT 300')->results();
foreach ($queueList as $queue) {
    $tg->chatId = $queue['chatId'];
    $tg->sendMessage($queue['text']);

    switch ($queue['lang']) {
        case 'ru':
            $text = $ru_text;
            break;
        case 'ua':
            $text = $ua_text;
            break;
        case 'en':
            $text = $en_text;
            break;
    }
    switch ($queue['mode']) {
        case 'done':
            $tg->sendMessageWithKeyboard($text['mainMenu'], [
                [$text['todayForecast'], $text['weeklyForecast']],
                [$text['currentWeather']],
                [$text['feedback'], $text['settings']]]);
            break;
        case 'locationType':
            $buttons = [
                [$text['chooseFromList']],
                [$text['sendCityName']],
            ];
            $tg->sendMessageWithKeyboard($text['howChooseCityQuestion'], $buttons);
            break;
        case 'lang':
            $tg->sendMessageWithKeyboard('
🇺🇦 Виберіть мову з клавіатури нижче
🇷🇺 Выберите язык с клавиатуры ниже
🇺🇸 Select language the from keyboard',
                [[ChangeLanguageService::LANG_TEXT_UA, ChangeLanguageService::LANG_TEXT_RU, ChangeLanguageService::LANG_TEXT_EN]]);
            break;
    }

    $db->query('DELETE FROM usersQueue WHERE id = ' . $queue['id']);
}
