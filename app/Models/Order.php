<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['office_id', 'supply_id', 'quantity', 'month_needed'];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function lineTotal(): float
    {
        return round($this->supply->markedUpPrice() * $this->quantity, 2);
    }
}
