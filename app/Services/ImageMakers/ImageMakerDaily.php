<?php

namespace App\Services\ImageMakers;

class ImageMakerDaily extends BaseImageMaker
{

    private $textLength;
    private $doubleTemp = false;
    private $tempY;
    private $tempX;

    public function cityName()
    {
    }

    public function date()
    {
        $this->weather_data = [
            [], [], [], [], [], []
        ];
        foreach ($this->weather_data as $item) {
            $positionX = (int)($this->width * 0.04);
            $this->doubleTemp ?
                $positionY = (int)(($this->height * 0.15) - (($this->textLength / 1.3) * 0.2)) :
                $positionY = (int)(($this->height * 0.15) - ($this->textLength * 0.13));

            $text = "     " . '12:00';

            $this->simplePlace($text, 16, null, $positionX, $positionY, self::MONTSERRAT_LIGHT);
        }
    }

    public function weatherDescription()
    {
        $positionX = (int)($this->tempX + ($this->width * 0.15));
        $positionY = 0;

        foreach ($this->weather_data as $item) {
            $positionY += (int)($this->height * 0.145);
            $this->simplePlace($item['weatherDesc'], 18, null, $positionX, $positionY);
        }
    }

    public function temperature()
    {
        $this->tempX = (int)($this->start_x + ($this->width * 0.16));
        $tempY = 0;

        foreach ($this->weather_data as $item) {
            if ($item['temp_min'] == $item['temp_max']) {
                $text = '     ' . $item['currentTemp'] . '°' . '     ';
            } else {
                $this->doubleTemp = true;
                $text = $item['temp_min'] . '° .. ' . $item['temp_max'] . '°';
            }

            $tempY += (int)($this->height * 0.15);
            $box = $this->placeBox(24, $text, $tempY);
            $this->textLength = (int)($box['add'][2] - $box['add'][0]);
            $this->simplePlace($text, 24, $box, $this->tempX, $tempY);
        }

    }

    public function weatherIcon()
    {
        $positionY = 0;
        $positionX = (int)($this->width * 0.438);

        foreach ($this->weather_data as $item) {
            $this->doubleTemp ?
                $positionY = (int)($positionY / 1.3) :
                $positionY = (int)($positionY / 1.6);

            $positionX = (int)($this->tempX + ($this->width * 0.15));

            $this->placeIcon(self::ICON_DATA[$item['weatherId']], 0.06, $positionX, $positionY);
        }
    }

    public function sunrise_sunset()
    {
    }

    public function wind()
    {
        $positionY = 0;
        $positionX = (int)($this->width * 0.438);

        foreach ($this->weather_data as $item) {
            $positionY += (int)($this->height * 0.15);
            $text = $this->text['wind'] . ' ' . round($item['windSpeed'], 0) . " м/с";
            $this->simplePlace($text, 70, null, $positionX, $positionY, self::MONTSERRAT_LIGHT);
        }
    }


    public function clouds()
    {
    }

    public function border()
    {
        $positionY = 0;

        for ($i = 1; $i <= count($this->weather_data); $i++) {
            $positionY += (int)($this->height * 0.15);

            $rectangleColor = imagecolorallocatealpha($this->image, 200, 200, 200, 90);

            $x1 = $this->start_x;
            $y1 = (int)($positionY - ($this->height * 0.085));

            imagefilledrectangle($this->image, $x1, $y1, $x1 + ($this->width * 0.12), $y1 + ($this->height * 0.12), $rectangleColor);

            $x1 = (int)($this->start_x + ($this->width * 0.16));
            $x2 = (int)($this->width * 0.9);
            $y1 = $y2 = (int)($positionY + ($this->height * 0.04));

            $white = imagecolorallocate($this->image, 255, 255, 255);
            imageline($this->image, $x1, $y1, $x2, $y2, $white);
        }
    }

    function background()
    {
        $color = imagecolorallocatealpha($this->image, 0, 0, 0, 50);
        imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $color);
    }

    function humidity()
    {
    }

    function tgLink()
    {
        $text = "t.me/synoptic_bot";

        $positionY = (int)(($this->height / 2) * 1.95);
        $box = $this->placeBox(65, $text, $positionY, self::MONTSERRAT_LIGHT);
        $positionX = (int)(($this->width / 2) - (($box['add'][2] - $box['add'][0]) / 2));

        $this->simplePlace($text, 65, null, $positionX, $positionY, self::MONTSERRAT_LIGHT);
    }

}