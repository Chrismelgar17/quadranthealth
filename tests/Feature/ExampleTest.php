<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    /**
     * Test the VAPI status callback route.
     */
    public function test_vapi_status_callback_route(): void
    {
        $vapiCallId = 'a60a0819-e0f3-4854-9614-0ebab8cf3545';
        $response = $this->postJson("/api/vapi-status-callback/{$vapiCallId}", [
            // Add any required payload here
        ]);

        $response->assertStatus(200);
    }

    // Add other test methods here

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->post('/api/incoming-call');
        


        $response->assertStatus(200);
    }
}
