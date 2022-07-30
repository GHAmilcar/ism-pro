<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index(){


        $total_ventas = Payment::where('paid_status','full_paid')->sum('total_amount');
        $total_ventas += Payment::where('paid_status', 'partial_paid')->sum('paid_amount');
        $total_pending = Payment::where('paid_status','partial_paid')->orWhere('paid_status','full_due')->sum('due_amount');
        $sales_products = InvoiceDetail::all()->sum('selling_qty');
        //$most_sales = InvoiceDetail::all()->orderBy('id', 'desc')->max(10);
         $date = Carbon::now();
         $date = $date->format('Y-m-d');
         $dateDB = Invoice::whereDate('created_at', $date)->get()->count();

        //   var_dump($dateDB);
        //   dd();
        $KPI = [
            'total_sales' => $total_ventas,
            'total_pending' => $total_pending,
            'date_sales' => $dateDB,
            'sales_products' => $sales_products,
            //'most_sales' => $most_sales,
        ];

        $latest_transactions = InvoiceDetail::all();
        return view('admin.index')->with([
            'latest_transactions' => $latest_transactions,
            'KPI' => $KPI,
        ]);
    }
}
