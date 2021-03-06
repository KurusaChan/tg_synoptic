<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $table = 'message';
    protected $fillable = ['user_id', 'text'];
    const UPDATED_AT = null;

}