<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'room_id',
        'tenant_id',
        'total_amount',
        'amount_paid',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the amount due for the invoice.
     */
    protected function amountDue(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['total_amount'] - $attributes['amount_paid']
        );
    }
}