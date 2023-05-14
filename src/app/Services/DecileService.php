<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DecileService
{
    public static function decile($subQuery)
    {
        //1. 購買IDごとにまとめる
        $subQuery = DB::table($subQuery)
            ->where('status', true)
            ->groupBy('id')
            ->selectRaw('id, customer_id, sum(subtotal) totalPerPurchase');

        //2. 会員ごとにまとめて購入金額順にソートする
        $subQuery =  DB::table($subQuery)
            ->groupBy('customer_id')
            ->selectRaw('sum(totalPerPurchase) total')
            ->orderBy('total', 'desc');

        //3. レコードを10等分にして1~10のグループに分けて連番を振る
        $subQuery = DB::table($subQuery)
            ->selectRaw('total, NTILE(10) OVER(ORDER BY total DESC) decile');

        //4. デシル番号ごとの合計金額と平均を取得
        $subQuery = DB::table($subQuery)
            ->groupBy('decile')
            ->selectRaw('decile, ROUND(avg(total)) average, sum(total) totalPerGroup');

        //5. デシル番号ごとの全体に占める割合を取得
        $data = DB::table($subQuery)
            ->selectRaw('decile, average, totalPerGroup, ROUND(100 * totalPerGroup / SUM(totalPerGroup) OVER(), 1) totalRatio')->get();

        $labels = $data->pluck('decile');
        $totals = $data->pluck('totalPerGroup');

        return  [$data, $labels, $totals];
    }
}
