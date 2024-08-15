<?php

namespace Tests\Unit;

use App\Models\Calls;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a call.
     */
    public function test_create_call(): void
    {
        $call = Calls::factory()->create([
            'request_type' => 'Test Request',
            'transcript' => 'Test Transcript',
            'summary' => 'Test Summary',
        ]);

        $this->assertDatabaseHas('calls', [
            'request_type' => 'Test Request',
            'transcript' => 'Test Transcript',
            'summary' => 'Test Summary',
        ]);
    }

    /**
     * Test updating a call.
     */
    public function test_update_call(): void
    {
        $call = Calls::factory()->create();

        $call->update([
            'summary' => 'Updated Summary',
        ]);

        $this->assertDatabaseHas('calls', [
            'id' => $call->id,
            'summary' => 'Updated Summary',
        ]);
    }

    /**
     * Test deleting a call.
     */
    public function test_delete_call(): void
    {
        $call = Calls::factory()->create();

        $call->delete();

        $this->assertDatabaseMissing('calls', [
            'id' => $call->id,
        ]);
    }

    /**
     * Test retrieving a call by ID.
     */
    public function test_get_call_by_id(): void
    {
        $call = Calls::factory()->create();

        $foundCall = Calls::find($call->id);

        $this->assertEquals($call->id, $foundCall->id);
    }
}