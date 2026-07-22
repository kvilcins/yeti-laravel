<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_renders_for_a_guest(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_the_homepage_lists_an_active_lot(): void
    {
        $lot = Item::factory()->create(['title' => 'Vintage brass telescope']);

        $this->get('/')->assertStatus(200)->assertSee($lot->title, false);
    }
}
