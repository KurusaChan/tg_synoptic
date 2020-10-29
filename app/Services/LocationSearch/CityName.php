<?php

namespace App\Services\LocationSearch;

use App\Models\City;

class CityName
{

    protected $title;
    protected $image_list = [];
    protected $lang;

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @return array|null
     */
    public function getCityButtons()
    {
        $possible_city_list = City::where('title_ua', 'like', '%' . $this->title . '%')
            ->orWhere('title_ru', 'like', '%' . $this->title . '%')
            ->orWhere('title_en', 'like', '%' . $this->title . '%')
            ->get();

        $city_list = [];
        if ($possible_city_list->count()) {
            foreach ($possible_city_list as $key => $city) {
                $city_list[] = [
                    $city->{'title_' . strtolower($this->lang)} . ', ' . $city->district->{'title_' . strtolower($this->lang)} . ' область'
                ];

                $this->image_list[] = 'https://synoptic.kurusa.uno/src/district/' . $city->district->id . '.jpeg';
            }
            return $city_list;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getImageList(): array
    {
        return $this->image_list;
    }

}