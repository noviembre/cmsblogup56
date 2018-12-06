<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name', 'slug'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    #---if u see page no found when you got to blog.dev/tag/php (and this tags
    # exits) that means you forgot to create the below method (getRouteKeyName)
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
