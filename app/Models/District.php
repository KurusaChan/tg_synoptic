<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model {

    protected $table = 'district';
    protected $fillable = ['title_ua', 'title_ru', 'title_en', 'selected_title'];
    const UPDATED_AT = null;

}