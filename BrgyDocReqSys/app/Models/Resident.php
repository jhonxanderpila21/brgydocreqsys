<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'household_id',
        'full_name',
        'address',
        'purok_zone',
        'date_of_birth',
        'civil_status',
        'occupation',
        'contact_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }
}
