<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a user.
     */
    public function test_create_user(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    }

    /**
     * Test updating a user.
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create();

        $user->update([
            'name' => 'Jane Doe',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Doe',
        ]);
    }

    /**
     * Test deleting a user.
     */
    public function test_delete_user(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test retrieving a user by ID.
     */
    public function test_get_user_by_id(): void
    {
        $user = User::factory()->create();

        $foundUser = User::find($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }
}