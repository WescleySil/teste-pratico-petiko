<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory()->count(5)->create(['user_id' => 1]);
        Task::factory()->count(5)->create(['user_id' => 2]);
    }
}
