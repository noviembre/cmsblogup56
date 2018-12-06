<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;


class BlogController extends BackendController
{
    //protected $limit = 5;
    protected $uploadPath;
    public function __construct()
    {
        parent::__construct();
        $this->uploadPath = public_path('app/img');
    }
    public function index(Request $request)
    {
        $onlyTrashed = FALSE;

        #--- view trashed posts ---------------------------------
        if (($status = $request->get('status')) && $status == 'trash')
        {
            $posts = Post::onlyTrashed()->with('category', 'author')
                ->latest()->paginate($this->limit);
            $postCount   = Post::onlyTrashed()->count();
            $onlyTrashed = TRUE;
        }

        #--- view all published posts ---------------------------------
        elseif ($status == 'published')
        {
            $posts = Post::published()->with('category', 'author')
                ->latest()->paginate($this->limit);
            $postCount   = Post::published()->count();
        }

        #--- view all scheduled posts ---------------------------------
        elseif ($status == 'scheduled')
        {
            $posts = Post::scheduled()->with('category', 'author')
                ->latest()->paginate($this->limit);
            $postCount   = Post::scheduled()->count();
        }
        #--- view all dratf posts ---------------------------------
        elseif ($status == 'draft')
        {
            $posts = Post::draft()->with('category', 'author')
                ->latest()->paginate($this->limit);
            $postCount   = Post::draft()->count();
        }

        #--- filter for show only current user posts
        elseif ($status == 'own')
        {
            $posts       = $request->user()->posts()->with('category', 'author')->latest()->paginate($this->limit);
            $postCount   = $request->user()->posts()->count();
        }

        else
        #--- view all posts ---------------------------------
        {
            $posts = Post::with('category', 'author')
                ->latest()->paginate($this->limit);
            $postCount   = Post::count();

        }
        $statusList = $this->statusList($request);
        return view("backend.blog.index", compact(
            'posts',
            'postCount',
            'onlyTrashed',
            'statusList'
        ));
    }

    private function statusList($request)
    {
        return [
            #--- show only current user posts
            'own'       => $request->user()->posts()->count(),

            'all'       => Post::count(),
            'published' => Post::published()->count(),
            'scheduled' => Post::scheduled()->count(),
            'draft'     => Post::draft()->count(),
            'trash'     => Post::onlyTrashed()->count(),
        ];
    }
    
    public function create(Post $post)
    {
        return view('backend.blog.create', compact(
            'post'
        ));
    }
    public function store(PostRequest $request)
    {
        //este codigo dormido pertenece a la correccion que hizo el tutor(fuera de la explicacion)
//        $data = $this->handleRequest($request);
//        $request->user()->posts()->create($data);
        $data = $this->handleRequest($request);

        $newPost = $request->user()->posts()->create($data);
        $newPost->createTags($data['post_tags']);

        return redirect('/backend/blog')
            ->with('message', 'Your post was created successfully!');
    }
    private function handleRequest($request)
    {
        #--- send data from request data
        $data = $request->all();
        if ($request->hasFile('image'))
        {
            $image       = $request->file('image');
            $fileName    = $image->getClientOriginalName();
            $destination = $this->uploadPath;

            $image->move($destination, $fileName);
            $data['image'] = $fileName;
        }

        return $data;
    }
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view("backend.blog.edit", compact(
            'post'
        ));
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $oldImage = $post->image;
        $data = $this->handleRequest($request);

        $post->update($data);
        $post->createTags($data['post_tags']);


        if ($oldImage !== $post->image) {
            $this->removeImage($oldImage);
        }
        return redirect('/backend/blog')
            ->with('message', 'Your post was updated successfully!');

    }
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();

        return redirect('/backend/blog')
            ->with('trash-message', ['Your post moved to Trash', $id]);
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->back()
            ->with('message', 'You post has been moved from the Trash');
    }

    public function forceDestroy($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();
        $this->removeImage($post->image);

        return redirect('/backend/blog?status=trash')
            ->with('message', 'Your post has been deleted successfully');
    }
    private function removeImage($image)
    {
        if ( ! empty($image) )
        {
            $imagePath     = $this->uploadPath . '/' . $image;
            $ext           = substr(strrchr($image, '.'), 1);
            $thumbnail     = str_replace(".{$ext}", "_thumb.{$ext}", $image);
            $thumbnailPath = $this->uploadPath . '/' . $thumbnail;

            if ( file_exists($imagePath) ) unlink($imagePath);
            if ( file_exists($thumbnailPath) ) unlink($thumbnailPath);
        }
    }


}
