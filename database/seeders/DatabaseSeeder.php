<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Voter;
use App\Models\Assistant;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      

         Admin::factory()->count(3)->create();
         Voter::factory()->count(3)->create();
           Assistant::factory()->count(3)->create();
           SuperAdmin::factory()->count(3)->create();
         $this->call(RolesAndPermissionsSeeder::class);

 
            //  \App\Models\Admin::factory()->count(3)->create();

       // $this->call(RolesAndPermissionsSeeder::class);
    }
}
