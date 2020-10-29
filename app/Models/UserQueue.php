<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQueue extends Model {

    protected $table = 'user_queue';
    protected $fillable = ['chat_id', 'message', 'message_id', 'status'];

}