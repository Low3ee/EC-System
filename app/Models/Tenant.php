<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'room_id', 
        'base_rent', 
        'due_day', 
        'lease_start', 
        'is_active'
    ];

    /**
     * Link back to the Room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Link to Invoices
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Dashboard Helper: Calculate Outstanding Balance
     */
    public function getBalanceAttribute()
    {
        // Total they were supposed to pay minus what they actually paid
        return $this->invoices->sum('amount_due') - $this->invoices->sum('amount_paid');
    }

    protected static function booted()
{
    static::created(function ($tenant) {
        $tenant->room->update(['is_available' => false]);
    });

    static::deleted(function ($tenant) {
        $tenant->room->update(['is_available' => true]);
    });
}
}