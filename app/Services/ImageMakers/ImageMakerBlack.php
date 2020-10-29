<?php

namespace App\Services\ImageMakers;

/*
 * city_name => назва міста
 * weather_description => опис погоди
 * current_temperature => поточна температура
 * weather_id => айді погоди
 * sunrise => схід сонця
 * sunset => захід сонця
 * wind_speed => швидкість вітру
 * clouds => хмарність
 * */

class ImageMakerBlack extends BaseImageMaker {

    public function cityName()
    {
        $this->simplePlace($this->weather_data['cityName'], 31, 0.12);
    }

    public function date()
    {
        $text = $this->text[date('l')] ? mb_strtoupper($this->text[date('l')]) : strtoupper(date('l'));
        $text .= " " . strtoupper(date('h:i a'));

        $this->simplePlace($text, 40, 0.18);
    }

    public function weatherDescription()
    {
        $this->simplePlace($this->weather_data['weatherDesc'], 40, 0.75);
    }

    public function temperature()
    {
        $text = strtoupper($this->weather_data['currentTemp'] . '°');
        $this->simplePlace($text, 13, 0.7);
    }

    public function weatherIcon()
    {
        $this->placeIcon(self::ICON_DATA[$this->weather_data['weatherId']], 0.28, 2, 2.4);
    }

    public function sunrise_sunset()
    {
        $coo = $this->placeIcon(SITE_ROOT . '/app/Services/ImageMakers/templates/sunrise.png', 0.1, 5, 1.2);

        $text = time() > $this->weather_data['sunrise'] ? $this->text['sunrise'] : $this->text['sunset'];
        $this->simplePlace($text, 50, null, $coo['dstX'], ($coo['dstY'] + ($coo['resH'] / 0.85)));

        $text = time() > $this->weather_data['sunrise'] ? date('H:i', $this->weather_data['sunset']) : date('H:i', $this->weather_data['sunrise']);
        $this->simplePlace($text, 30, null, ($coo['dstX'] * 0.97), ($coo['dstY'] + ($coo['resH'] / 0.6)));
    }

    public function wind()
    {
        $coo = $this->placeIcon(SITE_ROOT . '/app/Services/ImageMakers/templates/wind.png', 0.1, 2, 1.2);

        $this->simplePlace($this->text['wind'], 50, null, ($coo['dstX'] + ($coo['resW'] / 8)), ($coo['dstY'] + ($coo['resH'] / 0.85)));

        $text = $this->weather_data['windSpeed'] . ' м/с';
        $this->simplePlace($text, 30, null, $coo['dstX'], ($coo['dstY'] + ($coo['resH'] / 0.61)));
    }

    public function clouds()
    {
        $coo = $this->placeIcon(SITE_ROOT . '/app/Services/ImageMakers/templates/clouds.png', 0.1, 1.26, 1.2);

        $this->simplePlace($this->text['cloudsBtm'], 50, null, ($coo['dstX'] + ($coo['resW'] / 8)), ($coo['dstY'] + ($coo['resH'] / 0.85)));

        $text = $this->weather_data['clouds'] . '%';
        $this->simplePlace($text, 30, null, ($coo['dstX'] / 0.96), ($coo['dstY'] + ($coo['resH'] / 0.61)));
    }

    public function border()
    {
        $style = [imagecolorallocate($this->image, 255, 255, 255)];
        imagesetstyle($this->image, $style);
        imagearc($this->image, ($this->width / 2), ($this->height / 1.27), 30, 1, 0, 360, IMG_COLOR_STYLED);
    }

    function background()
    {
    }

    function humidity()
    {
    }

    function tgLink()
    {
        // TODO: Implement tgLink() method.
    }
}