<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bug;
use App\Models\BugStatusHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // ==== USERS ====
        $qa = User::updateOrCreate(
            ['email' => 'qa@test.com'],
            [
                'name' => 'QA User',
                'password' => Hash::make('password123'),
                'role' => 'QA',
            ]
        );

        $dev = User::updateOrCreate(
            ['email' => 'dev@test.com'],
            [
                'name' => 'Developer User',
                'password' => Hash::make('password123'),
                'role' => 'DEV',
            ]
        );

        $pm = User::updateOrCreate(
            ['email' => 'pm@test.com'],
            [
                'name' => 'Project Manager',
                'password' => Hash::make('password123'),
                'role' => 'PM',
            ]
        );

        // ==== BUGS ====
        $severities = ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'];

        for ($i = 1; $i <= 5; $i++) {
            $bug = Bug::updateOrCreate(
                ['title' => "Test Bug #$i"],
                [
                    'description' => "This is test bug number $i",
                    'reproduction_steps' => "1. Step one\n2. Step two\n3. Step three",
                    'severity' => $severities[array_rand($severities)],
                    'status' => 'OPEN',
                    'reporter_id' => $qa->id,
                    'assignee_id' => $dev->id,
                ]
            );

            BugStatusHistory::updateOrCreate(
                [
                    'bug_id' => $bug->id,
                    'new_status' => 'OPEN',
                ],
                [
                    'user_id' => $qa->id,
                    'old_status' => null,
                    'notes' => 'Bug reported from seeder',
                ]
            );
        }
    }
}
