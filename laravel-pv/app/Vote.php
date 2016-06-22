<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $primaryKey = 'submission_id';

    protected $fillable = [
        'submission_id',
        'user_id',
        'vote_type'
    ];

    public $timestamps = false;

    public function setUpdatedAtAttribute($value)
    {

    }

    public function setUpdatedAt($val)
    {}

    public function setCreatedAt($val)
    {}
}
