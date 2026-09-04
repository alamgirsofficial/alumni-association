<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniInfo extends Model
{
    use HasFactory;

    protected $table = 'alumni_info';

    protected $fillable = [
        'user_id',
        'member_id',
        'full_name',
        'mobile_number',
        'address',
        'batch',
        'passing_year',
        'roll_number',
        'registration_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}