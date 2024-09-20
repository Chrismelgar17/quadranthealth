<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic', function (Blueprint $table) {
            $table->id();
            $table->string('medical_practice_phone_number'); // Medical Practice Phone Number
            $table->string('clinic_phone_number'); // Our phone number (Twilio number we setup for the clinic)
            $table->string('clinic_name'); // Clinic name
            $table->string('clinic_address'); // Clinic address
            $table->string('clinic_hours'); // Clinic hours
            $table->string('additional_clinic_goals'); // Additional clinic goals
            $table->string('first_message'); // First message
            $table->string('vapi_assistant_id'); // VAPI Assistant ID
            $table->string('scheduling_link'); // Scheduling link
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic');
    }
};
