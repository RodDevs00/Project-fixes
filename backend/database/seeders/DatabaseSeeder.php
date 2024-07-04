<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * CREATE USER ROLES.
         */
        \App\Models\UserRole::create(['role' => 'Admin']);
        \App\Models\UserRole::create(['role' => 'User']);


        /**
         * CREATE ADMINISTRATOR ACCOUNT.
         * FEEL FREE TO CHANGE EMAIL AND PASSWORD
         */
        \App\Models\User::create([
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'user_role_id'  => 1,
            'account_activated' => true
        ]);

        // CREATE DUMMY USERS
        \App\Models\User::factory(10)->create();
        \App\Models\UserInfo::factory(11)->create();
    }
}
