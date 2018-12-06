@extends('layouts.main')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">

            @if (! $posts->count())
                <div class="alert alert-warning">
                    <p>Nothing Found</p>
                </div>
            @else

            @include('blog.alert')


            @foreach($posts as $post)

                <article class="post-item">
                    @if ($post->image_url)
                        <div class="post-item-image">
                            <a href="{{ route('blog.show', $post->slug ) }}">
                                <img src="{{ $post->image_url }}" alt="">
                            </a>
                        </div>
                    @endif

                    <div class="post-item-body">
                        <div class="padding-10">
                            <h2><a href="{{ route('blog.show', $post->slug ) }}">{{ $post->title }}</a></h2>
                            <p>{{ $post->excerpt }}</p>
                        </div>

                        <div class="post-meta padding-10 clearfix">
                            <div class="pull-left">
                                <ul class="post-meta-group">
                                    <li><i class="fa fa-user"></i>
                                        <a href="{{ route('author', $post->author->slug ) }}"> {{ $post->author->name }}</a>
                                    </li>
                                    <li><i class="fa fa-clock-o"></i>
                                        <time> {{ $post->date }}</time>
                                    </li>
                                    <li><i class="fa fa-folder"></i>
                                        <a href="{{ route('category', $post->category->slug ) }}">
                                            {{ $post->category->title }}
                                        </a>
                                    </li>


                                    <li><i class="fa fa-comments"></i>
                                        @foreach($post->tags as $tag)


                                        <a href="{{ route('tag', $tag->slug) }}">
                                            {{ $tag->name }}
                                        </a>

                                        @endforeach
                                        <!--
                                        video 061
                                        se salto del min 04:55 - 07:14
                                        debido a que no tenemos HTML
                                        -->

                                    </li>


                                    <li>
                                        <i class="fa fa-comments"></i>
                                        <a href="{{ route('blog.show', $post->slug ) }}#post-comments">
                                            {{ $post->commentsNumber() }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('blog.show', $post->slug ) }}">Continue Reading &raquo;</a>
                            </div>
                        </div>
                    </div>
                </article>

            @endforeach

            @endif


            <nav class="text-center">
                
                {{ $posts->appends(request()->only([ 'month', 'year']))->links() }}
                
            </nav>

        </div>

        @include('layouts.sidebar')

    </div>
</div>

@endsection