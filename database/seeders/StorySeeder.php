<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stories')->truncate();
        DB::table('story_details')->truncate();
        DB::table('stories')->insert([
            ['id' => 1, 'name' => 'Chuế tế', 'link' => 'https://truyenfull.vn/o-re-chue-te/','created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
    }
}
