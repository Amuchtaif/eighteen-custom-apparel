<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_legacy_admin_url_returns_404(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(404);

        $response2 = $this->get('/admin/login');
        $response2->assertStatus(404);
    }
}
