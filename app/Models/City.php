<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

    protected $table = 'city';
    protected $fillable = ['district_id', 'city_id', 'title_ua', 'title_ru', 'title_en', 'weight'];
    public $with = ['district'];
    const UPDATED_AT = null;

    public function district()
    {
        return $this->belongsTo(District::class);
    }

}