<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\Post;
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #------delete all content of Tags
        DB::table('tags')->truncate();

        #--- Tag # 1
        $php = new Tag();
        $php->name = "PHP";
        $php->slug = "php";
        $php->save();

        #--- Tag # 2
        $laravel = new Tag();
        $laravel->name = "Laravel";
        $laravel->slug = "Laravel";
        $laravel->save();

        #--- Tag # 3
        $symphony = new Tag();
        $symphony->name = "Symphony";
        $symphony->slug = "symphony";
        $symphony->save();

        #--- Tag # 4
        $vue = new Tag();
        $vue->name = "Vue JS";
        $vue->slug = "vuejs";
        $vue->save();

        #-------
        $tags = [
            $php->id,
            $laravel->id,
            $symphony->id,
            $vue->id
        ];

        foreach (Post::all() as $post)
        {
            #-------
            shuffle($tags);

            for ($i = 0; $i < rand(0, count($tags)-1); $i++)
            {
                $post->tags()->detach($tags[$i]);
                $post->tags()->attach($tags[$i]);
            }
        }
    }
}
