<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'user';
    protected $fillable = ['is_blocked', 'user_name', 'first_name', 'chat_id', 'lang', 'status'];
    public $with = ['userCity'];

    public function userCity()
    {
        return $this->hasMany(UserCity::class);
    }

}