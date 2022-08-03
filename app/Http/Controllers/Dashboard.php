<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function index(){


        $total_ventas = Payment::where('paid_status','full_paid')->sum('total_amount');
        $total_ventas += Payment::where('paid_status', 'partial_paid')->sum('paid_amount');
        $total_pending = Payment::where('paid_status','partial_paid')->orWhere('paid_status','full_due')->sum('due_amount');
        $sales_products = InvoiceDetail::all()->sum('selling_qty');
        $most_sales = DB::table('invoice_details')
        ->join('products', 'invoice_details.product_id', '=', 'products.id')
        ->select('products.name as name', 'invoice_details.product_id', DB::raw('SUM(selling_qty) as total_sale'))
        ->take(10)
        ->where('invoice_details.status','=',1)
        ->groupBy('invoice_details.product_id','products.name')
        ->orderBy('selling_qty', 'desc')
        ->get();

        $least_sold = DB::table('invoice_details')
        ->join('products', 'invoice_details.product_id', '=', 'products.id')
        ->select('products.name as name', 'invoice_details.product_id', DB::raw('SUM(selling_qty) as total_sale'))
        ->take(10)
        ->where('invoice_details.status','=',1)
        ->groupBy('invoice_details.product_id','products.name')
        ->orderBy('selling_qty', 'asc')
        ->get();

         $date = Carbon::now();
         $date = $date->format('Y-m-d');
         $dateDB = Invoice::whereDate('created_at', $date)->get()->count();

        //   var_dump($most_sales);
        //   dd();
        $KPI = [
            'total_sales' => $total_ventas,
            'total_pending' => $total_pending,
            'date_sales' => $dateDB,
            'sales_products' => $sales_products,

        ];

        $latest_transactions = InvoiceDetail::all();
        return view('admin.index')->with([
            'latest_transactions' => $latest_transactions,
            'KPI' => $KPI,
            'most_sales' => $most_sales,
            'least_sold' => $least_sold
        ]);
    }
}
