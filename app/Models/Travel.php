<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Travel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travels';

    protected $fillable = [
        'travel_mode',
        'origin',
        'destination',
        'purpose',
        'amount',
        'passengers',
        'travel_date',
        'return_date',
        'notes',
        'itinerary_path',
    ];

    protected $casts = [
        'travel_date'      => 'date',
        'return_date'      => 'date',
        'share_expires_at' => 'datetime',
        'amount'           => 'decimal:2',
    ];

    /** Human-readable travel mode label */
    public function getModeIconAttribute(): string
    {
        return match ($this->travel_mode) {
            'air'  => '✈️',
            'sea'  => '🚢',
            'land' => '🚌',
            default => '🧳',
        };
    }

    /** Check if the share link is still active */
    public function getShareActiveAttribute(): bool
    {
        return $this->share_token
            && $this->share_expires_at
            && Carbon::now()->isBefore($this->share_expires_at);
    }
}
