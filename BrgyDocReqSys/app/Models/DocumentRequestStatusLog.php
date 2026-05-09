<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequestStatusLog extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'document_request_id',
        'status',
        'remarks',
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}
