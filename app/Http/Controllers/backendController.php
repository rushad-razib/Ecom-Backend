<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class backendController extends Controller
{
    function dashboard(){
        // $sales = Order::where('created_at', '>', Carbon::now()->subDays(7))
        // ->groupBy(DB::raw('DATE(created_at)'))
        // ->selectRaw("sum(total) as sales_amount, DATE_FORMAT(created_at, '%d-%b') as date")
        // ->get();
        // return $sales;

        $sales = Order::where('created_at', '>', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw("SUM(total) as sales_amount, DATE(created_at) as date")
            ->get();

        $total_sales = [];
        $date = [];
        foreach($sales as $sale){
            $total_sales[] = $sale->sales_amount;
            $date[] = Carbon::parse($sale->date)->format('d-M');
        }
        return view('backend.dashboard', [
            'total_sales'=>$total_sales,
            'date'=>$date,
        ]);
    }
}
