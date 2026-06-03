<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Travel extends Model
{
    use HasFactory, softDeletes;

     protected $table = 'travels';

    protected $fillable = [
        'share_token',
        'share_expires_at',
        'travel_mode',
        'transport_provider',
        'origin',
        'destination',
        'purpose',
        'amount',
        'passengers',
        'itinerary_path',
        'travel_date',
        'return_date',
        'notes',
    ];

    protected $casts = [
        'share_expires_at' => 'datetime',
        'travel_date'  => 'date',
        'return_date'  => 'date',
        'amount'       => 'decimal:2',
        'passengers'   => 'integer',
    ];

    // Human-readable mode label with icon
    public function getModeIconAttribute(): string
    {
        return match($this->travel_mode) {
            'air'  => '✈️',
            'sea'  => '🚢',
            'land' => '🚌',
            default => '🚗',
        };
    }

    public function getModeLabelAttribute(): string
    {
        return match($this->travel_mode) {
            'air'  => 'Air',
            'sea'  => 'Sea',
            'land' => 'Land',
            default => ucfirst($this->travel_mode),
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₱ ' . number_format($this->amount, 2);
    }
}
