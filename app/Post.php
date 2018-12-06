<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'published_at',
        'category_id',
        'image',
    ];
    protected $dates = ['published_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute($value)
    {
        $imageUrl = "";

        if ( ! is_null($this->image))
        {
            $imagePath = public_path() . "/app/img/" . $this->image;
            if (file_exists($imagePath))
                $imageUrl = asset("/app/img/" . $this->image);
        }

        return $imageUrl;
    }

    public function getImageThumbUrlAttribute($value)
    {
        $imageUrl = "";

        if ( ! is_null($this->image))
        {
            $ext       = substr(strrchr($this->image, '.'), 1);
            $thumbnail = str_replace(".{$ext}", "_thumb.{$ext}", $this->image);
            $imagePath = public_path() . "/app/img/" . $thumbnail;
            if (file_exists($imagePath)) $imageUrl = asset("app/img/" . $thumbnail);
        }

        return $imageUrl;
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    #-------------Recien se usa para la fecha
    public function getDateAttribute($value)
    {
        return is_null($this->published_at) ? '' : $this->published_at->diffForHumans();
    }


    #-----------List Latest Post first -----------------
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    #------- view post from today to yesterday (no futures) -------

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }



    /*
    |--------------------------------------------------------------------------
    | Backend | blog
    |--------------------------------------------------------------------------
    |
    | views/backend/blog
    |
    |
    */

    #====== blog | index =======================================================
    public function dateFormatted($showTimes = false)
    {
        $format = "d/m/Y";
        if ($showTimes) $format = $format . " H:i:s";
        return $this->created_at->format($format);
    }

    public function publicationLabel()
    {
        if ( ! $this->published_at) {
            return '<span class="label label-warning">Draft</span>';
        }
        elseif ($this->published_at && $this->published_at->isFuture()) {
            return '<span class="label label-info">Schedule</span>';
        }
        else {
            return '<span class="label label-success">Published</span>';
        }
    }

    #====== blog | index close ==================================================

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now() );
    }

    public function scopeScheduled($query)
    {
        return $query->where("published_at", ">", Carbon::now());
    }

    public function scopeDraft($query)
    {
        return $query->whereNull("published_at");
    }

    #====== blog | create open ==================================================
    public function setPublishedAtAttribute($value)
    {
        #--- if there is date save it otherwise save as null
        $this->attributes['published_at'] = $value ?: NULL;
    }



    
    public static function archives()
    {
        return static::selectRaw('count(id) as post_count, year(published_at) year, monthname(published_at) month')
            ->published()
            ->groupBy('year', 'month')
            ->orderByRaw('min(published_at) desc')
            ->get();
    }

    public function scopeFilter($query, $filter)
    {
        if (isset($filter['month']) && $month = $filter['month']) {
            $query->whereRaw('month(published_at) = ?', [Carbon::parse($month)->month]);
        }

        if (isset($filter['year']) && $year = $filter['year']) {
            $query->whereRaw('year(published_at) = ?', [$year]);
        }

        // check if any term entered
        if (isset($filter['term']) && $term = $filter['term'])
        {
            $query->where(function($q) use ($term) {
                // $q->whereHas('author', function($qr) use ($term) {
                //     $qr->where('name', 'LIKE', "%{$term}%");
                // });
                // $q->orWhereHas('category', function($qr) use ($term) {
                //     $qr->where('title', 'LIKE', "%{$term}%");
                // });
                $q->orWhere('title', 'LIKE', "%{$term}%");
                $q->orWhere('excerpt', 'LIKE', "%{$term}%");
            });
        }
    }


    public function commentsNumber($label = 'Comment')
    {
        $commentsNumber = $this->comments->count();

        return $commentsNumber . " " . str_plural($label, $commentsNumber);
    }

    public function createComment(array $data)
    {
        $this->comments()->create($data);
    }

    public function createTags($str)
    {
        $tags = explode(",", $str);
        $tagIds = [];

        foreach ($tags as $tag)
        {
            $newTag = Tag::firstOrCreate(
                ['slug' => str_slug($tag)], ['name' => trim($tag)]
            );

            $tagIds[] = $newTag->id;
        }

        $this->tags()->sync($tagIds);

      #  este codigo dormido es la version larga de ($this->tags()->sync($tagIds);)
      #  $this->tags()->detach();
      #  $this->tags()->attach($tagIds);
    }

    public function getTagsListAttribute()
    {
        return $this->tags->pluck('name');
    }



}
