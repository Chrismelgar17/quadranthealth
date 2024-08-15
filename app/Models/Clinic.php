<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $table = 'clinic';

    protected $fillable = [
        'medical_practice_phone_number',
        'clinic_phone_number',
        'clinic_name',
        'clinic_address',
        'clinic_hours',
        'additional_clinic_goals',
        'first_message',
    ];

    // Create a new clinic record
    public static function createClinic($data)
    {
        return self::create($data);
    }

    // Read a clinic record by ID
    public static function getClinicById($id)
    {
        return self::find($id);
    }

    // Update a clinic record by ID
    public static function updateClinic($id, $data)
    {
        $clinic = self::find($id);
        if ($clinic) {
            $clinic->update($data);
            return $clinic;
        }
        return null;
    }

    // Delete a clinic record by ID
    public static function deleteClinic($id)
    {
        $clinic = self::find($id);
        if ($clinic) {
            return $clinic->delete();
        }
        return false;
    }
}  