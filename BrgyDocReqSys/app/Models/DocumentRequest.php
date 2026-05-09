<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_READY = 'ready_for_pickup';
    public const STATUS_RELEASED = 'released';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'resident_id',
        'document_type_id',
        'purpose',
        'status',
        'payment_amount',
        'payment_date',
        'receipt_number',
        'is_paid',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_READY => 'Ready for pickup',
            self::STATUS_RELEASED => 'Released',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(DocumentRequestStatusLog::class)->orderBy('created_at', 'desc');
    }
}
