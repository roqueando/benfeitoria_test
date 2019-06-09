<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model {

    protected $fillable = [
        'title', 'image', 'text',
    ];

    public $timestamps = false;

    const CREATED_AT = 'createdAt';

    protected $table = 'posts';
}
