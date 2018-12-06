<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'author_name',
        'author_email',
        'author_url',
        'body',
        'post_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    #-----in this project we do not use Markdown
    #-- thats why I will disable it
//    public function getBodyHtmlAttribute()
//    {
//        return Markdown::convertToHtml(e($this->body));
//    }
}
