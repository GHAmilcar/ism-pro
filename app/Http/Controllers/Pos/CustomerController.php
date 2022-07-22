<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Auth;
use Illuminate\Support\Carbon;
use Image;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Validation\Rules\Exists;

class CustomerController extends Controller
{
    public function CustomerAll(){

        $customers = Customer::latest()->get();

        return view('backend.customer.customer_all',compact('customers'));

    } // End Method


    public function CustomerAdd(){
     return view('backend.customer.customer_add');
    }    // End Method


    public function CustomerStore(Request $request){

        Customer::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'dui' => $request->dui,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Customer Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);

    } // End Method


    public function CustomerEdit($id){

       $customer = Customer::findOrFail($id);
       return view('backend.customer.customer_edit',compact('customer'));

    } // End Method


    public function CustomerUpdate(Request $request){

        $customer_id = $request->id;

        Customer::findOrFail($customer_id)->update([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'dui' => $request->dui,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);


    } // End Method


     public function CustomerDelete($id){

        $payment_client = Payment::where('customer_id', $id)->first();

            if (isset($payment_client) === false) {
             Customer::findOrFail($id)->delete();
             $notification = array(
                 'message' => 'Customer Deleted Successfully',
                 'alert-type' => 'success'
             );

            }
            else if(isset($payment_client) === true){
             $notification = array(
                 'message' => 'Sorry, you can not delete this customer, the customer has pending credits',
                 'alert-type' => 'warning'
             );
            }

         return redirect()->back()->with($notification);

     } // End Method


    public function CreditCustomer(){

        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('backend.customer.customer_credit')->with([
            'allData' => $allData,
        ]);

    } // End Method


    public function CreditCustomerPrintPdf(){

        $allData = Payment::whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('backend.pdf.customer_credit_pdf',compact('allData'));

    }// End Method


    public function CustomerEditInvoice($invoice_id){

        $payment = Payment::where('invoice_id',$invoice_id)->first();
        return view('backend.customer.edit_customer_invoice')->with([
            'payment' => $payment,
        ]);
    }// End Method


    public function CustomerUpdateInvoice(Request $request,$invoice_id){

        if ($request->new_paid_amount < $request->paid_amount) {

            $notification = array(
            'message' => 'Sorry You Paid Maximum Value',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
        }
        elseif($request->paid_status == 'Select Status'){

            $notification = array(
                'message' => 'Please Choose an a paid method and add quantity',
                'alert-type' => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        else{
            $payment = Payment::where('invoice_id',$invoice_id)->first();
            $payment_details = new PaymentDetail();
            $payment->paid_status = $request->paid_status;

            if ($request->paid_status == 'full_paid') {
                 $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->new_paid_amount;
                 $payment->due_amount = '0';
                 $payment_details->current_paid_amount = $request->new_paid_amount;

            } elseif ($request->paid_status == 'partial_paid') {
                $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount']+$request->paid_amount;
                $payment->due_amount = Payment::where('invoice_id',$invoice_id)->first()['due_amount']-$request->paid_amount;
                $payment_details->current_paid_amount = $request->paid_amount;

            }

            $payment->save();
            $payment_details->invoice_id = $invoice_id;
            $payment_details->date = date('Y-m-d',strtotime($request->date));
            $payment_details->updated_by = Auth::user()->id;
            $payment_details->save();

            $notification = array(
                'message' => 'Invoice Update Successfully',
                'alert-type' => 'success'
        );
        return redirect()->route('credit.customer')->with($notification);


        }

    }// End Method



    public function CustomerInvoiceDetails($invoice_id){

        $payment = Payment::where('invoice_id',$invoice_id)->first();
        $payment_details = PaymentDetail::where('invoice_id',$payment->invoice_id)->get();

        return view('backend.pdf.invoice_details_pdf',compact('payment','payment_details'));

    }// End Method

    public function PaidCustomer(){
        $allData = Payment::where('due_amount','=','0')->get();
        return view('backend.customer.customer_paid',compact('allData'));
    }// End Method

    public function PaidCustomerPrintPdf(){

        $allData = Payment::where('due_amount','=','0')->get();
        return view('backend.pdf.customer_paid_pdf',compact('allData'));
    }// End Method


    public function CustomerWiseReport(){

        $customers = Customer::all();
        return view('backend.customer.customer_wise_report',compact('customers'));

    }// End Method


    public function CustomerWiseCreditReport(Request $request){

         $allData = Payment::where('customer_id',$request->customer_id)->whereIn('paid_status',['full_due','partial_paid'])->get();
        return view('backend.pdf.customer_wise_credit_pdf',compact('allData'));
    }// End Method


    public function CustomerWisePaidReport(Request $request){

         $allData = Payment::where('customer_id',$request->customer_id)->where('paid_status','!=','full_due')->get();
        return view('backend.pdf.customer_wise_paid_pdf',compact('allData'));
    }// End Method



}
