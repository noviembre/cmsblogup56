<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use Illuminate\Http\Request;
use App\Post;

class CommentsController extends Controller
{

    public function store(Post $post, CommentStoreRequest $request)
    {
        $post->createComment($request->all());

        return redirect()->back()
            ->with('message', "Your comment successfully send.");
    }

}
