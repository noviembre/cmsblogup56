<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\User;
use App\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $limit = 3;


    public function index()
    {
        $posts = Post::with('author', 'tags', 'category', 'comments')
            ->latestFirst()
            ->published()            
            ->filter(request()->only(['year', 'month']))
            ->simplePaginate($this->limit);
        
        return view("blog.index", compact(
            'posts'
        ));
    }

    public function show(Post $post)
    {
        $post->increment('view_count');
        #Remember:
            # - $post->comments. calling comments without parenthesis will return collections.
        # however $post->comments(), calling comments with parenthesis will return eloquent relation object
        $postComments = $post->comments()->simplePaginate(3);


        return view("blog.show", compact(
            'post',
            'postComments'
        ));
    }


    public function category(Category $category)
    {
        #-------- it shows the Category Name that you choose to view.
        $categoryName = $category->title;
        //\DB::enableQueryLog();
        $posts = $category->posts()
            ->with('author', 'tags','comments')
            ->latestFirst()
            ->published()
            ->simplePaginate($this->limit);

       return view("blog.index",
            compact('posts',  'categoryName')
        );
        //dd(\DB::getQueryLog());
    }

    public function author(User $author)
    {
        $authorName = $author->name;

        // \DB::enableQueryLog();
        $posts = $author->posts()
            ->with('category','tags','comments')
            ->latestFirst()
            ->published()
            ->simplePaginate($this->limit);

        return view("blog.index", compact('posts', 'authorName'));
    }

    public function tag(Tag $tag)
    {
        $tagName = $tag->title;

        $posts = $tag->posts()
            ->with('author', 'category','comments')
            ->latestFirst()
            ->published()
            ->simplePaginate($this->limit);

        return view("blog.index", compact('posts', 'tagName'));
    }





}
