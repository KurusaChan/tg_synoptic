<?php

namespace app\Services\ImageMakers;

define('SITE_ROOT', $_SERVER["DOCUMENT_ROOT"]);

use CURLFile;

abstract class BaseImageMaker
{

    public $width = 600;
    public $height = 800;

    const MONTSERRAT_LIGHT = SITE_ROOT . '/app/Services/ImageMakers/fonts/Montserrat-Light.otf';
    const MONTSERRAT_THIN = SITE_ROOT . '/app/Services/ImageMakers/fonts/Montserrat-Thin.otf';

    const ICON_DATA = [
        200 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 201 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 202 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 210 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 211 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 212 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 221 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 230 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 231 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png', 232 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/11d.png',

        300 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 301 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 302 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 310 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 311 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 312 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 313 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 314 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 321 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png',

        500 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 501 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/10d.png', 502 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 503 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 504 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 511 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 520 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 521 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 522 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png', 531 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/09d.png',

        600 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 601 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 602 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 611 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 612 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 615 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 616 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 620 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 621 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png', 622 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/13d.png',

        701 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 711 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 721 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 731 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 741 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 751 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 761 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 762 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 771 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png',

        800 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/01d.png', 801 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/02d.png', 802 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/03d.png', 803 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/04d.png', 804 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/04d.png',

        903 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 904 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 905 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 906 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png',

        951 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 952 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 953 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 954 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 955 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 956 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 957 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 958 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 959 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 960 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png', 962 => SITE_ROOT . '/app/Services/ImageMakers/weather_icon/light/d/50d.png',
    ];

    public $image;
    protected $weather_data;
    protected $weekly_data;
    protected $text;
    protected $default_font_size;
    protected $default_font_file;
    protected $start_x;
    protected $start_y;

    protected $city_name;
    protected $weather_description;
    protected $current_temperature;
    protected $weather_id;
    protected $sunrise;
    protected $sunset;
    protected $wind_speed;
    protected $cloud;


    public function setDefaultFontsize($default_font_size)
    {
        $this->default_font_size = $default_font_size;
    }

    public function setDefaultFontfile($default_font_file)
    {
        $this->default_font_file = $default_font_file;
    }

    public function setImage($path = null)
    {
        if ($path) {
            $this->image = imagecreatefrompng($path);
            $this->width = imagesx($this->image);
            $this->height = imagesy($this->image);
            $this->start_x = (int)($this->width * 0.03);
            $this->start_y = (int)($this->height * 0.03);
        } else {
            $this->image = imagecreatetruecolor($this->width, $this->height);
        }
    }

    public function setWeatherData($weather_data)
    {
        $this->weather_data = $weather_data;
    }

    public function setWeeklyData($weekly_data)
    {
        $this->weekly_data = array_slice($weekly_data, 0, 5);
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function constructImage()
    {
        $this->cityName();
        $this->background();
        $this->tgLink();
        $this->border();
        $this->date();
        $this->weatherDescription();
        $this->temperature();
        $this->weatherIcon();
        $this->sunrise_sunset();
        $this->wind();
        $this->clouds();
        $this->humidity();
    }

    public function getImage()
    {
        $tmpfilename = tempnam('/tmp', 'img');
        imagepng($this->image, $tmpfilename);

        return $tmpfilename;

        $bot_url = 'https://api.telegram.org/bot';
        $bot_url .= self::TELEGRAM_API_KEY . '/sendPhoto';

        $post_fields = [
            'chat_id' => $chatId, 'photo' => new CURLFile($tmpfilename),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:multipart/form-data',
        ));
        curl_setopt($ch, CURLOPT_URL, $bot_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

        curl_exec($ch);

        unlink($tmpfilename);
    }

    protected function simplePlace($text, $fontSize, $box = null, $x = null, $y = null, $fontFile = null)
    {
        $fontColor = imagecolorallocate($this->image, 255, 255, 255);
        $fs = (int)($this->width / $fontSize);
        $coo = $box ? $this->placeBox($fs, $text, $box) : '';

        imagefttext($this->image, $fs, 0, $box ? $coo['x'] : $x, $box ? $coo['y'] : $y, $fontColor, $fontFile ? $fontFile : $this->default_font_file, $text);
    }

    protected function placeBox(int $fontSize, $text, $posY, $fontFile = null): array
    {
        $box = imagettfbbox($fontSize, 0, $fontFile ? $fontFile : $this->default_font_file, $text);
        $positionX = (int)(($this->width / 2) - (($box[2] - $box[0]) / 2));
        $positionY = (int)($this->height * $posY);

        return ['x' => $positionX, 'y' => $positionY, 'add' => $box];
    }

    protected function placeIcon($icon, $needKoef, $x, $y, $full = false)
    {
        $iconToPaste = imagecreatefrompng($icon);
        $iconWidth = imagesx($iconToPaste);
        $iconHeight = imagesy($iconToPaste);

        if ($full) {
            $dstX = $dstY = 0;
            $x2 = $this->width;
            $y2 = $this->height;
        } else {
            $need = (int)($this->width * $needKoef);
            $koef = $need / $iconWidth;
            $resultW = $iconWidth * $koef;
            $resultH = $iconHeight * $koef;

            $x2 = $resultW;
            $y2 = $resultH;

            $dstX = (int)(($this->width / $x) - ($resultW / 2)); //в першій половині - чим менше число, тим більше вправо
            $dstY = (int)(($this->height / $y) - ($resultH / 2)); //в першій половині - чим більше число, тим більше вверх
        }

        imagecopyresampled(// Указатели на изображения, КУДА и ОТКУДА нужно скопировать картинку
            $this->image, $iconToPaste, //Ресурс целевого изображения. //Ресурс исходного изображения.

            //Координаты точки результирующего изображения, куда поместить ЛЕВЫЙ ВЕРХНИЙ УГОЛ иконки
            $dstX, $dstY, //x-координата результирующего изображения.  //y-координата результирующего изображения.

            //Координаты точки на иконке, начиная с которой нужно копировать (левый верхний угол). Поскольку копируем всю картинку, нули
            0, 0, //x-координата исходного изображения. //y-координата исходного изображения.

            // Ширина и высота, которые должна получить иконка после копирования
            $x2, $y2, //Результирующая ширина.  //Результирующая высота.
            $iconWidth, $iconHeight);

        return [
            'dstX' => $dstX, 'dstY' => $dstY,
            'resW' => $resultW, 'resH' => $resultH
        ];
    }

    abstract function background();

    abstract function cityName();

    abstract function date();

    abstract function weatherDescription();

    abstract function temperature();

    abstract function weatherIcon();

    abstract function sunrise_sunset();

    abstract function wind();

    abstract function clouds();

    abstract function border();

    abstract function humidity();

    abstract function tgLink();


    /**
     * @param mixed $city_name
     */
    public function setCityName($city_name): void
    {
        $this->city_name = $city_name;
    }

    /**
     * @param mixed $weather_description
     */
    public function setWeatherDescription($weather_description): void
    {
        $this->weather_description = $weather_description;
    }

    /**
     * @param mixed $current_temperature
     */
    public function setCurrentTemperature($current_temperature): void
    {
        $this->current_temperature = $current_temperature;
    }

    /**
     * @param mixed $weather_id
     */
    public function setWeatherId($weather_id): void
    {
        $this->weather_id = $weather_id;
    }

    /**
     * @param mixed $sunrise
     */
    public function setSunrise($sunrise): void
    {
        $this->sunrise = $sunrise;
    }

    /**
     * @param mixed $sunset
     */
    public function setSunset($sunset): void
    {
        $this->sunset = $sunset;
    }

    /**
     * @param mixed $wind_speed
     */
    public function setWindSpeed($wind_speed): void
    {
        $this->wind_speed = $wind_speed;
    }

    /**
     * @param mixed $cloud
     */
    public function setCloud($cloud): void
    {
        $this->cloud = $cloud;
    }

}