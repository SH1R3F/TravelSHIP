<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToursListTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_tours_sorted_by_starting_date(): void
    {
        $travel = Travel::factory()->create();
        $second = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addMinutes(5)
        ]);
        $first = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()
        ]);


        $response = $this->json('GET', "/api/v1/travels/{$travel->slug}/tours");


        $response->assertStatus(200)
            ->assertSeeInOrder([$first->id, $second->id]);
    }

    public function test_it_returns_paginated_tours_data(): void
    {
        $travel = Travel::factory()->create();
        Tour::factory(16)->create(['travel_id' => $travel->id]);

        $response = $this->json('GET', "/api/v1/travels/{$travel->slug}/tours");

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('meta.last_page', 2);
    }
}
