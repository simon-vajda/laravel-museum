<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users_count = rand(5, 10);
        $users = collect();
        for ($i = 0; $i < $users_count; $i++) {
            $users->add(\App\Models\User::factory()->create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@szerveroldali.hu',
                'password' => bcrypt('password'),
            ]));
        }

        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@szerveroldali.hu',
            'password' => bcrypt('adminpwd'),
            'is_admin' => true,
        ]);
        $users->add($admin);

        $labels_count = rand(6, 10);
        $labels = \App\Models\Label::factory($labels_count)->create();

        $items_count = rand(15, 20);
        $items = \App\Models\Item::factory($items_count)->create();

        foreach ($items as $item) {
            for ($i = 0; $i < rand(0, 10); $i++) {
                try {
                    $item->labels()->attach($labels->random());
                } catch (\Exception $e) {
                    // Ignore duplicate label
                }
            }
        }

        $comments_count = rand(20, 30);
        $comments = collect();

        for ($i = 0; $i < $comments_count; $i++) {
            $comments->add(\App\Models\Comment::factory()->create([
                'item_id' => $items->random()->id,
                'author_id' => $users->random()->id,
            ]));
        }
    }
}
