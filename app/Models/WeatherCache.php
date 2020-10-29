<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherCache extends Model {

    protected $table = 'weather_cache';
    protected $fillable = ['city_id', 'owm_callback', 'sinoptik_callback', 'for_date', 'status'];
    const UPDATED_AT = null;

}