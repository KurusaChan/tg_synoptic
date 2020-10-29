<?php
require_once(SITE_ROOT . '/vendor/autoload.php');
$db = \PHPtricks\Orm\Database::connect();
$tg = new \App\TgHelpers\TelegramApi($db);
$users = $db->query('SELECT * FROM userList LIMIT 300 OFFSET 5900')->results();

$photo = 'https://synoptic.kurusa.zhecky.net/src/kukumber.jpg';
$caption = '<a href="http://t.me/cucumberapps"> Кукумбер </a> — <b>канал про найкорисніші додатки та програми для ваших гаджетів. </b>

<a href="http://t.me/cucumberapps"> Кукумбер </a> не включає сюди поштові клієнти, соціальні мережі на кшталт Facebook, Instagram або Youtube, оскільки про них і так всі знають і всі ними користуються. Тут будуть менш популярні додатки, але не менш важливі, корисні та цікаві.';

$blocked = 0;
$ok = 0;
foreach ($users as $user) {
    $tg->sendPhoto($photo, $caption, $user['chatId']);
    $result = $tg->result;
    if ($result["error_code"] == 403 || $result["description"] == "Forbidden: bot was blocked by the user") {
        $blocked++;
    } elseif ($result['ok'] === true) {
        $ok++;
    } else {
        echo $result['description'];
    }
}
echo 'blocked: ' . $blocked . '-----' . 'ok: ' . $ok;
