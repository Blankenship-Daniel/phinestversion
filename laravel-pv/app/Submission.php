<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public $timestamps = false;

    public function setUpdatedAtAttribute($value)
    {

    }

    protected $fillable = [
        'song_id',
        'show_id',
        'description',
        'user_id',
        'score'
    ];

    public function setUpdatedAt($val)
    {}

    public function setCreatedAt($val)
    {}
}
