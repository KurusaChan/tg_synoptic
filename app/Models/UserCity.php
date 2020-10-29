<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCity extends Model {

    protected $table = 'user_city';
    protected $fillable = ['user_id', 'district_id', 'city_id'];
    public $with = ['city'];
    const UPDATED_AT = null;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

}