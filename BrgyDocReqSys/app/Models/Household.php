<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'purok_zone',
    ];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
