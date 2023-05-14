<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;
use Carbon\Carbon;

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

    public function scopeBetweenDate($query, $startDate = null, $endDate = null)
    {
        $carbonDate = Carbon::parse($endDate);
        $carbonDate->setTime(23, 59, 59);
        $endDate = $carbonDate->format('Y-m-d H:i:s');
        
        if (is_null($startDate) && is_null($endDate)) {
            return $query;
        }
        if (!is_null($startDate) && is_null($endDate)) {
            return $query->where('created_at', '>=', $startDate);
        }
        if (is_null($startDate) && !is_null($endDate)) {
            return $query->where('created_at', '<=', $endDate);
        }
        if (!is_null($startDate) && !is_null($endDate)) {
            return $query->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate);
        }
    }
}
