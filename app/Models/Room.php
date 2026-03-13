<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_number',
        'type',
        'default_rent',
        'capacity',
        'is_available',
    ];

    /**
     * Get the tenants associated with the room.
     * * A Room "Has Many" Tenants.
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * The utilities associated with the room.
     */
    public function utilities(): BelongsToMany
    {
        return $this->belongsToMany(Utility::class, 'room_utility')
                    ->withPivot('amount', 'description')
                    ->withTimestamps();
    }
}