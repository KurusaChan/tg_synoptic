<?php

namespace App\Services\WeatherService;

use App\Models\City;
use Goutte\Client;

class ParserSinoptik
{

    /**
     * @param int $city_id
     * @param int $date
     * @return array
     */
    public static function parse(int $city_id, int $date)
    {
        $city = City::where('city_id', $city_id)->get();

        $result = [];
        $url = 'https://ua.sinoptik.ua/погода-';

        $url .= str_replace(' ', '-', mb_strtolower($city[0]->title_ua)) . '/';
        $url .= date('Y-m-d', $date);
        $client = new Client();
        $crawler = $client->request('GET', $url, [
            //     'proxy' => '54.234.254.119:80'
        ]);

        for ($i = 0; $i <= 7; $i++) {
            // 0:00, 2:00...
            $time = str_replace(' ', '', trim($crawler->filter('.gray.time > td')->getNode($i)->textContent));
            if ($time) {
                if (strlen($time) == 4) {
                    $result[$i]['time'] = '0';
                    $result[$i]['time'] .= $time;
                } else {
                    $result[$i]['time'] = $time;
                }
                $result[$i]['desc'] = mb_strtolower($crawler->filter('.img.weatherIcoS > td')->eq($i)->first()->filter('.weatherIco')->attr('title'));
                $result[$i]['humidity'] = $crawler->filter('tr')->eq(6)->filter('td')->eq($i)->text();
                $result[$i]['rains'] = $crawler->filter('tr')->eq(8)->filter('td')->eq($i)->text();
            }
        }
        $result['description'] = $crawler->filter('.weatherIco')->getNode(1)->getAttribute('title');
        $result['detail'] = $crawler->filter('.wDescription > .rSide')->first()->text();

        return $result;
    }

}