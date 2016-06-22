<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'id',
        'title',
        'alias_for',
        'tracks_count',
        'slug'
    ];

    public function setUpdatedAt($val)
    {}

    public function setCreatedAt($val)
    {}
}
