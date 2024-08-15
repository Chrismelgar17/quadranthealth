<?php

namespace Tests\Unit;

use App\Models\Clinic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a clinic.
     */
    public function test_create_clinic(): void
    {
        $clinic = Clinic::factory()->create([
            'medical_practice_phone_number' => '123-456-7890',
            'clinic_phone_number' => '098-765-4321',
            'clinic_name' => 'Test Clinic',
            'clinic_address' => '123 Test Street',
            'clinic_hours' => '9 AM - 5 PM',
            'additional_clinic_goals' => 'Provide quality care',
            'first_message' => 'Welcome to our clinic!',
        ]);

        $this->assertDatabaseHas('clinic', [
            'medical_practice_phone_number' => '123-456-7890',
            'clinic_phone_number' => '098-765-4321',
            'clinic_name' => 'Test Clinic',
            'clinic_address' => '123 Test Street',
            'clinic_hours' => '9 AM - 5 PM',
            'additional_clinic_goals' => 'Provide quality care',
            'first_message' => 'Welcome to our clinic!',
        ]);
    }

    /**
     * Test updating a clinic.
     */
    public function test_update_clinic(): void
    {
        $clinic = Clinic::factory()->create();

        $clinic->update([
            'clinic_address' => '456 Updated Street',
        ]);

        $this->assertDatabaseHas('clinic', [
            'id' => $clinic->id,
            'clinic_address' => '456 Updated Street',
        ]);
    }

    /**
     * Test deleting a clinic.
     */
    public function test_delete_clinic(): void
    {
        $clinic = Clinic::factory()->create();

        $clinic->delete();

        $this->assertDatabaseMissing('clinic', [
            'id' => $clinic->id,
        ]);
    }

    /**
     * Test retrieving a clinic by ID.
     */
    public function test_get_clinic_by_id(): void
    {
        $clinic = Clinic::factory()->create();

        $foundClinic = Clinic::find($clinic->id);

        $this->assertEquals($clinic->id, $foundClinic->id);
    }
}