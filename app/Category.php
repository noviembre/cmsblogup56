<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [ 'title', 'slug' ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    #------------ view routes by id
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
