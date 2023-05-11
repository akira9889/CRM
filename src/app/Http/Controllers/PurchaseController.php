<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use Inertia\Inertia;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    const NOT_CANCELED = 1;
    const CANCELED = 0;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::selectRaw('id, customer_name, customer_kana, sum(subTotal) total, status, created_at')
        ->groupBy('id')
        ->searchCustomers($request->search)
        ->orderBy('created_at', 'desc')
        ->paginate(50);

        return Inertia::render('Purchases/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::select('id', 'name', 'price')
        ->where('is_selling', true)
        ->get();

        return Inertia::render('Purchases/Create', [
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => static::NOT_CANCELED
            ]);

            foreach ($request->items as $item) {
                $purchase->items()->attach($item['id'], [
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();

            return to_route('dashboard');

        } catch(\Exception $e) {
            DB::rollBack();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $orders = Order::where('id', $purchase->id)->get();

        $total = 0;
        foreach($orders as $order) {
            $total += $order->subtotal;
        }

        // dd($orders);

        return Inertia::render('Purchases/Show', [
            'orders' => $orders,
            'total' => $total
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $orders = Order::where('id', $purchase->id)->get();

        if (!$orders[0]->status) {
            abort(404);
        }

        $total = 0;
        foreach ($orders as $order) {
            $total += $order->subtotal;
        }

        return Inertia::render('Purchases/Edit', [
            'orders' => $orders,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $purchase->status = $request->status;
        $purchase->save();

        $items = [];
        foreach($request->items as $item) {
            $item_id = $item['id'];
            $item_quantity[$item_id] = ['quantity' => $item['quantity']];
            $items += $item_quantity;
        }
        $purchase->items()->sync($items);

        return to_route('purchases.show', [
            'purchase' => $purchase->id
        ])
        ->with([
            'message' => '更新しました。',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
