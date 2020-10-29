<?php
require_once(SITE_ROOT . '/vendor/autoload.php');

$db = \PHPtricks\Orm\Database::connect();
$forecast_code = [
    7 => 1,
    12 => 2,
    19 => 4
];
//$users = $db->query('SELECT * FROM userList WHERE cityId >= 27 AND done = 1 AND autoForecast&' . $forecast_code[date('H')])->results();
$users = $db->query('SELECT * FROM userList WHERE cityId >= 27 AND done = 1')->results();
$row = '';
/*foreach ($users as $key => $user) {
    end($users);
    $row .= '(' . $user['chatId'] . ', ' . $user['cityId'] . ', ' . $user['receivingType'] . ', ' . $user['lang'] . ')';
    if ($key !== key($users)) {
        $row .= ',';
    }
}*/

echo $row;
/*foreach ($users as $user) {
    AutoForecastModel::InsertUserIntoQueue($user['chatID'], $user['cityID'], $user['receiving'], $user['lang'], $user['vp']);
}*/


