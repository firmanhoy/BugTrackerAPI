<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bug;
use App\Models\BugStatusHistory;
use Illuminate\Database\Seeder;

class FactoryTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 5 user QA, 5 DEV, 2 PM
        User::factory()->count(5)->state(['role' => 'QA'])->create();
        User::factory()->count(5)->state(['role' => 'DEV'])->create();
        User::factory()->count(2)->state(['role' => 'PM'])->create();

        // 2. Buat 20 bug random
        $bugs = Bug::factory()->count(20)->create();

        // 3. History status awal (OPEN)
        foreach ($bugs as $bug) {
            BugStatusHistory::create([
                'bug_id' => $bug->id,
                'user_id' => $bug->reporter_id,
                'old_status' => null,
                'new_status' => 'OPEN',
                'notes' => 'Seeded bug created via factory',
            ]);
        }
    }
}
