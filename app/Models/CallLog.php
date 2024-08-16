<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_sid',
        'call_status',
        'from',
        'to',
        'duration',
        'timestamp',
    ];
}
