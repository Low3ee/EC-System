<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'room_number', 'base_rent', 'due_day', 'is_active'];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // A helper for your dashboard
    public function getBalanceAttribute()
    {
        return $this->invoices()->where('status', '!=', 'paid')->sum('amount_due') 
               - $this->invoices()->sum('amount_paid');
    }
}
?>