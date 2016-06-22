<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $fillable = [
        'id',
        'date',
        'tour_id',
        'venue_id'
    ];

    public function setUpdatedAt($val)
    {}

    public function setCreatedAt($val)
    {}
}
