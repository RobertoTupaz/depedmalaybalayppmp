<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group',
        'allocation',
        'prepared_by',
        'prepared_by_designation',
        'reviewed_by',
        'reviewed_by_designation',
        'approved_by',
        'approved_by_designation',
    ];

    protected function casts(): array
    {
        return [
            'allocation' => 'decimal:2',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function totalOrdered(): float
    {
        return $this->orders()
            ->join('supplies', 'orders.supply_id', '=', 'supplies.id')
            ->selectRaw('SUM(orders.quantity * ROUND(supplies.unit_price * 1.1, 2)) as total')
            ->value('total') ?? 0.0;
    }

    public function walletBalance(): float
    {
        return round((float) $this->allocation - $this->totalOrdered(), 2);
    }
}
