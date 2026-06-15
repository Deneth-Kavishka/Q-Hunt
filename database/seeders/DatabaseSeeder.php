<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Event;
use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'Participant']);

        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $participant = User::create([
            'name' => 'Test Team Leader',
            'email' => 'team@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);

        $event = Event::create([
            'name' => 'Sample QR Hunt 2026',
            'start_time' => now(),
            'end_time' => now()->addDays(2),
            'is_active' => true,
        ]);

        Team::create([
            'event_id' => $event->id,
            'leader_user_id' => $participant->id,
            'name' => 'The Seekers',
            'total_score' => 0,
        ]);
    }
}
