<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;

class Order extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new Subtotal);
    }

    public function scopeSearchCustomers($query, $input = null)
    {
        if ($input) {
            if (Order::where('customer_kana', 'like', $input . '%')
                ->orWhere('customer_tel', 'like', $input . '%')->exists()
            ) {
                return $query->where('customer_kana', 'like', $input . '%')
                    ->orWhere('customer_tel', 'like', $input . '%');
            }
        }
    }
}
