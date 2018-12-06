<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;
class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset the posts table
        DB::table('posts')->truncate();

        $posts = [];
        $faker = Factory::create();

        #------ creando una instancia de carbon,
        #----- $date = one year ago
        $date = Carbon::now()->modify('-1 year');


        // generate 36 dummy posts data
        for ($contador = 1; $contador <= 36; $contador++)
        {
            $image = "Post_Image_" . rand(1, 5) . ".jpg";
            
            #----this fake data will be like this:2018-10-02, 2018-10-22, 2018-11-02, etc
            #---- step foward 10 days
            $date->addDays(10);

            $publishedDate = clone ($date);
            #----new object
            $createdDate = clone ($date);

            $posts[] = [
                'author_id' => rand(1, 3),
                'title' => $faker->sentence(rand(8, 12)),
                'excerpt' => $faker->text(rand(250, 300)),
                'body' => $faker->paragraphs(rand(10, 15), true),
                'view_count'   => rand(1, 10) * 10,
                'slug' => $faker->slug(),
                #----- si el valor no es 1 entonces que sea nulo
                'image' => rand(0, 1) == 1 ? $image : NULL,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
                'published_at' => $contador < 30 ?  $publishedDate : ( rand(0, 1) == 0 ? NULL : $publishedDate->addDays(4)),
            ];
        }

        DB::table('posts')->insert($posts);
    }
}
