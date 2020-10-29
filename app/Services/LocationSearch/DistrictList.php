<?php

namespace App\Services\LocationSearch;

use App\Models\District;

class DistrictList
{

    public $lang;
    public $title;

    /**
     * @param mixed $lang
     */
    public function setLang($lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return District[]|array|\Illuminate\Database\Eloquent\Collection
     */
    public function getDistrictButtons()
    {
        $district_list = District::all();

        $district_buttons = [];
        foreach ($district_list as $district) {
            $district_buttons[] = [$district->{'title_' . strtolower($this->lang)}];
        }

        return $district_buttons;
    }

    /**
     * @return |null
     */
    public function searchDistrict()
    {
        $possible_district = District::where('title_ua', 'like', '%' . $this->title . '%')
            ->orWhere('title_ru', 'like', '%' . $this->title . '%')
            ->orWhere('title_en', 'like', '%' . $this->title . '%')
            ->get(['id']);
        if ($possible_district->count()) {
            return $possible_district[0]['id'];
        }

        return null;
    }

}