<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 5; $i++) {
            Todo::create(['title' => Str::random(10)]);
        }
    }
}
