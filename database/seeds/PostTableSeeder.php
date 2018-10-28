<?php

use Illuminate\Database\Seeder;
use App\Post;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            Post::create([
                'title' => $faker->title,
                'anons' => $faker->randomDigit,
                'text' => $faker->text,
                'tags' => $faker->randomLetter,
                'image' => $faker->url
            ]);
        }
    }
}
