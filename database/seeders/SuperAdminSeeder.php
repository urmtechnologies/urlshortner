<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = strtolower(trim((string) env('SUPERADMIN_EMAIL')));
        $password = (string) env('SUPERADMIN_PASSWORD');

        if ($email === '' || $password === '') {
            throw new RuntimeException(
                'Set SUPERADMIN_EMAIL and SUPERADMIN_PASSWORD in .env before seeding.'
            );
        }

        $exists = DB::table('users')->where('email', $email)->exists();

        if ($exists) {
            return;
        }

        DB::insert(
            'INSERT INTO users
                (name, email, password, role, invited_by_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                'Super Admin',
                $email,
                Hash::make($password),
                'superadmin',
                null,
                now(),
                now(),
            ]
        );
    }
}
