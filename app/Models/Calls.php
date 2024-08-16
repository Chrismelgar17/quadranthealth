<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Calls extends Model
{
    use HasFactory;

    protected $table = 'calls';

    protected $fillable = [
        'call_sid',
        'vapi_call_id',
        'patient_phone',
        'request_type',
        'transcript',
        'summary',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    // Read a call record by ID
    public static function getCallById($id)
    {
        return self::find($id);
    }
    
    // Upsert a call record
    public static function upsertCall($data)
    {
        return self::upsert(
            ['id' => $data['id']], // Assuming 'id' is the unique identifier
            $data
        );
    }

    // Delete a call record by ID
    public static function deleteCall($id)
    {
        $call = self::find($id);
        if ($call) {
            return $call->delete();
        }
        return false;
    }

}
