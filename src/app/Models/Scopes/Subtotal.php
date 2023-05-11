<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class Subtotal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $sql = DB::table('purchases')
            ->select(
                'purchases.id as id',
                'item_purchase.id as pivot_id',
                DB::raw('items.price * item_purchase.quantity as subtotal'),
                'customers.name as customer_name',
                'customers.kana as customer_kana',
                'customers.tel as customer_tel',
                'items.id as item_id',
                'items.name as item_name',
                'items.price as item_price',
                'item_purchase.quantity',
                'purchases.status',
                'purchases.created_at',
                'purchases.updated_at'
            )
            ->leftJoin('item_purchase', 'purchases.id', '=', 'item_purchase.purchase_id')
            ->leftJoin('items', 'item_purchase.item_id', '=', 'items.id')
            ->leftJoin('customers', 'purchases.customer_id', '=', 'customers.id');

        $builder->fromSub($sql, 'order_subtotals');

        //購入ID(purchase), 顧客名(customers), 購買ごとの合計金額(purchase_id(purchases), price(items), quantity(item_purchase)), 購買日(purchase)

        // $sql = "SELECT p.id, c.name, i.price, pivot.quantity, (i.price * pivot.quantity) subTotal, p.status, p.created_at
        //         FROM purchases p
        //         LEFT JOIN item_purchase pivot
        //         ON p.id = pivot.purchase_id
        //         LEFT JOIN items i
        //         ON pivot.item_id = i.id
        //         LEFT JOIN customers c
        //         ON p.customer_id = c.id";

        // $sql = "SELECT history.id, history.name, sum(history.subTotal) total, history.status
        //         FROM (
        //                 SELECT c.name, p.id, i.price, pivot.quantity, (i.price * pivot.quantity) subTotal, p.status, p.created_at
        //                 FROM purchases p
        //                 LEFT JOIN item_purchase pivot
        //                 ON p.id = pivot.purchase_id
        //                 LEFT JOIN items i
        //                 ON pivot.item_id = i.id
        //                 LEFT JOIN customers c
        //                 ON p.customer_id = c.id
        //             ) history
        //         GROUP BY history.id
        //      ";

    }
}
