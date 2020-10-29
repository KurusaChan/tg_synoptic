<?php

namespace App\Services\ImageMakers;

class ImageMakerMain extends BaseImageMaker {

    protected $circleWidth;
    protected $circleHeight;

    function background()
    {
        $color = imagecolorallocatealpha($this->image, 0, 0, 0, 80);
        imagefilledrectangle($this->image, 0, 0, $this->width, $this->height, $color);
    }

    function cityNaАme()
    {
    }

    function date()
    {
        $date = date("d/m \ H:i", $this->weather_data['timestampDt']);
        $fontSize = (int)($this->width * 0.018);
        $white = imagecolorallocate($this->image, 255, 255, 255);

        $centerX = (int)($this->width / 2);
        $box = imagettfbbox($fontSize, 0, $this->default_font_file, $date);
        $positionX = (int)($centerX - (($box[2] - $box[0]) / 2));
        $positionY = (int)(($this->height / 2) * 1.65);

        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, $this->default_font_file, $date);
    }

    function weatherDescription()
    {
        $fontSize = (int)($this->width * 0.023);

        $centerX = (int)($this->width / 2);
        $box = imagettfbbox($fontSize, 0, $this->default_font_file, $this->weather_data['weatherDesc']);
        $positionX = (int)($centerX - ($box[2] - $box[0]) / 2); //позиция начала текста //нижний правй-нижнийлевый   верхний правый-нижнийправый

        $centerY = (int)($this->height / 1.5);
        $positionY = (int)($centerY - ($box[4] - $box[2]) / 2) + $this->height * 0.03; //верхний правый-нижнийправый

        $white = imagecolorallocate($this->image, 255, 255, 255);

        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, $this->default_font_file, $this->weather_data['weatherDesc']);

    }

    function temperature()
    {
        $fontSize = (int)($this->width * 0.021);
        $white = imagecolorallocate($this->image, 255, 255, 255);

        $positionY = (int)(($this->height / 1.95));

        $minTemp = $this->text['min'] . round($this->weather_data['minTemp'], 0) . "°";
        $box = imagettfbbox($fontSize, 0, self::MONTSERRAT_LIGHT, $minTemp);
        $positionX = (int)(($this->width / 2) - ($this->circleWidth / 3) - ($box[2] - ($box[0])) / 2);
        $textLength = (int)($box[2] - $box[0]);
        imagesetstyle($this->image, [$white]);
        imagearc($this->image, $positionX + ($textLength / 2), $positionY + ($this->height * 0.01), $textLength, 1, 0, 360, IMG_COLOR_STYLED);
        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, self::MONTSERRAT_LIGHT, $minTemp);

        $maxTemp = $this->text['max'] . round($this->weather_data['maxTemp'], 0) . "°";
        $box = imagettfbbox($fontSize, 0, self::MONTSERRAT_LIGHT, $maxTemp);
        $positionX = (int)(($this->width / 2) + ($this->circleWidth / 3) - ($box[2] - ($box[0])) / 2);
        $textLength = (int)($box[2] - $box[0]);
        imagearc($this->image, $positionX + ($textLength / 2), $positionY + ($this->height * 0.01), $textLength, 1, 0, 360, IMG_COLOR_STYLED);
        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, self::MONTSERRAT_LIGHT, $maxTemp);

        $temp = round($this->weather_data['currentTemp'], 0) . "°";
        $fontSize = (int)($this->width * 0.08);
        $white = imagecolorallocate($this->image, 255, 255, 255);

        $centerX = (int)($this->width / 2);
        $box = imagettfbbox($fontSize, 0, self::MONTSERRAT_THIN, $temp);
        $positionX = (int)($centerX - (($box[2] - $box[0]) / 2.5));
        $positionY = (int)($this->height / 2 * 0.6);
        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, self::MONTSERRAT_THIN, $temp);

    }

    function weatherIcon()
    {
        $this->placeIcon(self::ICON_DATA[$this->weather_data['weatherId']], 0.15, 2, 2);
    }

    function sunrise_sunset()
    {
    }

    function wind()
    {
        $text = $this->text['wind'] . ' ' . round($this->weather_data['windSpeed'], 0) . " м/с";
        $fontSize = (int)($this->width * 0.023);

        $centerX = (int)($this->width / 2);
        $box = imagettfbbox($fontSize, 0, $this->default_font_file, $text);
        $positionX = (int)($centerX - ($box[2] - $box[0]) / 2); //позиция начала текста //нижний правй-нижнийлевый   верхний правый-нижнийправый

        $centerY = (int)($this->height / 1.5);
        $positionY = (int)($centerY - ($box[4] - $box[2]) / 2) + $this->height * 0.09; //верхний правый-нижнийправый

        $white = imagecolorallocate($this->image, 255, 255, 255);

        imagefttext($this->image, $fontSize, 0, $positionX, $positionY, $white, $this->default_font_file, $text);
    }

    function clouds()
    {
        $fontSize = (int)($this->width * 0.021);
        $white = imagecolorallocate($this->image, 255, 255, 255);

        $clouds = nl2br($this->text['cloudsBtm'] . "\n" . "    " . $this->weather_data['clouds'] . " %");
        $goodClouds = str_replace('<br />', '', $clouds);
        $box = imagettfbbox($fontSize, 0, $this->default_font_file, $clouds);
        $cloudsX = (int)(($this->width / 2) + ($this->circleWidth / 2.5) - ($box[2] - ($box[0])) / 2);
        $cloudsY = (int)(($this->height / 2));

        imagefttext($this->image, $fontSize, 0, $cloudsX, $cloudsY + ($this->height * 0.06), $white, self::MONTSERRAT_LIGHT, $goodClouds);

    }

    function border()
    {
        $circleColor = imagecolorallocatealpha($this->image, 0, 0, 0, 60);

        $this->circleHeight = (int)($this->height * 0.95);
        $this->circleWidth = $this->circleHeight;

        $circleX = (int)($this->width / 2);
        $circleY = (int)($this->height / 2);

        imagesetstyle($this->image, [imagecolorallocate($this->image, 255, 255, 255)]);

        //BORDER
        imagearc($this->image, $circleX, $circleY, $this->circleWidth / 1.05, $this->circleHeight / 1.05, 0, 360, IMG_COLOR_STYLED);
        imagearc($this->image, $circleX, $circleY, $this->circleWidth / 1.048, $this->circleHeight / 1.048, 0, 360, IMG_COLOR_STYLED);
        imagearc($this->image, $circleX, $circleY, $this->circleWidth / 1.046, $this->circleHeight / 1.046, 0, 360, IMG_COLOR_STYLED);
        //BORDER

        imagefilledellipse($this->image, $circleX, $circleY, $this->circleWidth, $this->circleHeight, $circleColor);
    }

    function humidity()
    {
        $fontSize = (int)($this->width * 0.021);

        $humidity = nl2br($this->text['humidityImg'] . "\n" . "     " . $this->weather_data['humidity'] . " %");
        $goodHumidity = str_replace('<br />', '', $humidity);
        $box = imagettfbbox($fontSize, 0, $this->default_font_file, $humidity);
        $humidityX = (int)(($this->width / 2) - ($this->circleWidth / 3.8) - ($box[2] - ($box[0])) / 2);
        $humidityY = (int)(($this->height / 2));
        $white = imagecolorallocate($this->image, 255, 255, 255);

        imagefttext($this->image, $fontSize, 0, $humidityX, $humidityY + ($this->height * 0.06), $white, self::MONTSERRAT_LIGHT, $goodHumidity);

    }

    function tgLink()
    {
        // TODO: Implement tgLink() method.
    }

    function cityName()
    {
        // TODO: Implement cityName() method.
    }
}