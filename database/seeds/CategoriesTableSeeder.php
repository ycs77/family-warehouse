<?php

use App\Category;
use App\Item;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class, 3)->create()->each(function ($category) {
            foreach (range(1, 3) as $i) {
                $category->items()->save(factory(Item::class)->make());
            }
        });
    }
}
