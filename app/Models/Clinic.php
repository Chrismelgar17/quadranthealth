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

    // Get all clinic records
    public static function getAll()
    {
        return self::all();
    }

    // Read a clinic record by ID
    public static function getClinicById($id)
    {
        return self::find($id);
    }

     // Upsert a clinic record
     public static function upsertClinic($data)
     {
         return self::upsert(
             $data,
            ['id'],
            $data

         );
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