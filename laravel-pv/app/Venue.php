<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'id',
        'name',
        'shows_count',
        'location',
        'slug'
    ];

    public function setUpdatedAt($val)
    {}

    public function setCreatedAt($val)
    {}
}
