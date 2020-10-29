<?php

namespace App\Services\ImageMakers;

class ImageMakerAlmostWeekly extends BaseImageMaker {

    public function cityName()
    {
        $this->simplePlace($this->weather_data['cityName'], 25, 0.13);
    }

    function background()
    {
        $this->placeIcon($this->weather_data['image'], 0, 0, 0, true);

        $color = imagecolorallocatealpha($this->image, 0, 0, 0, 50);
        imagefilledrectangle($this->image, 0, 0, 600, 800, $color);
    }

    function date()
    {
    }

    function weatherDescription()
    {
        $this->simplePlace($this->weather_data['weatherDesc'], 25, 0.18);
    }

    function temperature()
    {
        $this->simplePlace($this->weather_data['currentTemp'] . '°', 10, 0.28);

        $positionY = ($this->height * 0.45);
        foreach ($this->weekly_data as $data) {
            $positionY += 75;
            $this->simplePlace($data['currentTemp'] . '°', 30, null, ($this->width / 2.2), $positionY);
        }
    }

    function weatherIcon()
    {
        $this->placeIcon(self::ICON_DATA[$this->weather_data['weatherId']], 0.15, 2, 2.9);

        $positionY = (int)($this->height * 0.41);
        foreach ($this->weekly_data as $data) {
            $iconToPaste = imagecreatefrompng(self::ICON_DATA[$data['weatherId']]);
            $iconWidth = imagesx($iconToPaste);
            $iconHeight = imagesy($iconToPaste);

            $need = (int)($this->width * 0.1);
            $koef = $need / $iconWidth;
            $resultW = $iconWidth * $koef;
            $resultH = $iconHeight * $koef;

            $x2 = $resultW;
            $y2 = $resultH;

            $dstX = (int)(($this->width / 1.5));
            $positionY += 74;

            imagecopyresampled(
                $this->image, $iconToPaste,
                $dstX, $positionY,
                0, 0,
                $x2, $y2,
                $iconWidth, $iconHeight);
        }
    }

    function sunrise_sunset()
    {
    }

    function wind()
    {
    }

    function clouds()
    {
    }

    function border()
    {
        $positionY = (int)($this->height * 0.45);

        for ($i = 1; $i <= 5; $i++) {
            $positionY += 75;
            $text = $this->text[date('l', strtotime("+" . $i . " days"))] ? $this->text[date('l', strtotime("+" . $i . " days"))] : date('l', strtotime("+" . $i . " days"));
            $this->simplePlace($text, 30, null, 15, $positionY);
        }
    }

    function humidity()
    {
    }

    function tgLink()
    {
        // TODO: Implement tgLink() method.
    }
}