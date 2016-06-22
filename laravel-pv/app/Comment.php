<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'submission_id',
        'user_id',
        'comment',
        'updated_at',
        'created_at'
    ];
}
