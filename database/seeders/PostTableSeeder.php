<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post = new Post([
            'title' => 'Learning Laravel',
            'content' => 'This is a seed blog post.'
        ]);
        $post->save();

        $post = new Post([
            'title' => 'Something else',
            'content' => 'Some other seed content.'
        ]);
        $post->save();
    }
}
