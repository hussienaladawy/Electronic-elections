<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminElectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_elections_index_page()
    {
        $superAdmin = SuperAdmin::factory()->create();
        $this->actingAs($superAdmin, 'super_admin');

        Election::factory()->count(5)->create();

        $response = $this->get(route('super_admin.elections.index'));

        $response->assertStatus(200);
        $response->assertViewIs('super_admin.elections.index');
        $response->assertViewHas('elections');
    }
}
