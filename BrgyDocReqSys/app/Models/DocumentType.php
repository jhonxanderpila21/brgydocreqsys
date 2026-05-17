<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'processing_fee',
        'required_information',
    ];

    protected $casts = [
        'processing_fee' => 'decimal:2',
    ];

    public function requests()
    {
        return $this->hasMany(DocumentRequest::class);
    }
}
