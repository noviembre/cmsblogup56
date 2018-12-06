<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Post;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #--- created a new faker object
        $faker    = Factory::create();
        $comments = [];

        #---get the last 5 published post
        $posts = Post::published()->latest()->take(5)->get();
        foreach ($posts as $post)
        {
            #--- generate comments randomly for each post
            for ($i = 1; $i <= rand(1, 10); $i++)
            {
                #--- for the comment date we take from
                #-- the publication date of the post
                #-- and we increment 1 one for every comment
                $commentDate = $post->published_at->modify("+{$i} hours");

                $comments[] = [
                    'author_name' => $faker->name,
                    'author_email' => $faker->email,
                    'author_url' => $faker->domainName,
                    'body' => $faker->paragraphs(rand(1, 5), true),
                    'post_id' => $post->id,
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ];
            }
        }

        Comment::truncate();
        Comment::insert($comments);
    }
}
