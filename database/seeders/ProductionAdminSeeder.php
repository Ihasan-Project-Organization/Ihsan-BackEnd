<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = trim((string) env('ADMIN_EMAIL'));
        $password = (string) env('ADMIN_PASSWORD');

        if ($email === '' || $password === '') {
            $this->command?->warn('ADMIN_EMAIL and ADMIN_PASSWORD are required; production admin was not created.');

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'مدير منصة أنيس'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );

        Admin::updateOrCreate(
            ['user_id' => $user->id],
            ['admin_level' => 'super_admin']
        );
    }
}
