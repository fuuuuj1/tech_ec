<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCondition;
use App\Models\PrimaryCategory;

class SellController extends Controller
{
    public function showSellForm()
    {
        //$conditionsにitem_conditionsテーブルのクエリ結果を格納している
            // get()によりクエリを発行
            // orderby('引数1(カラムを指定)', '引数2(asc or desc)')によりクエリ結果のソートをする 未入力はasc
        $conditions = ItemCondition::orderBy('sort_no')->get();

        // N+1問題対処のために with()を利用してEager Loadingを実施
        $categories = PrimaryCategory::query()
            ->with([
                'secondaryCategories' => function($query){
                    $query->orderBy('sort_no');
                }
            ])
            ->orderBy('sort_no')
            ->get();

        return view('sell')
            ->with('conditions', $conditions)
            ->with('categories', $categories);
    }
}
