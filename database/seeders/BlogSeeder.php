<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use Faker\Factory as Faker;



class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            $batch = [];

            for ($j = 0; $j < 1000; $j++) {
                $batch[] = [
                    'title' => $faker->sentence,
                    'description' => $faker->paragraph,
                    'content' => $faker->paragraphs(3, true),
                    'author' => $faker->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Blog::insert($batch);
        }
    }
}
