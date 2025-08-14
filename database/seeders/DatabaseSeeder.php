<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      

         \App\Models\Admin::factory()->count(3)->create();
         \App\Models\Voter::factory()->count(3)->create();
           \App\Models\Assistant::factory()->count(3)->create();
           SuperAdmin::factory()->count(3)->create();
         $this->call(RolesAndPermissionsSeeder::class);

 
            //  \App\Models\Admin::factory()->count(3)->create();

       // $this->call(RolesAndPermissionsSeeder::class);
    }
}
