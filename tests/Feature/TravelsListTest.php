<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_paginated_travels_data(): void
    {
        Travel::factory(16)->create(['is_public' => 1]);

        $response = $this->json('GET', 'api/v1/travels');

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.last_page', 2);
    }

    public function test_it_returns_filtered_travels_by_is_public_property(): void
    {
        Travel::factory()->create(['is_public' => 0]);

        $response = $this->json('GET', 'api/v1/travels');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
