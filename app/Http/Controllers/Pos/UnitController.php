<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
     public function UnitAll(){

        $units = Unit::latest()->get();
        return view('backend.unit.unit_all',compact('units'));
    } // End Method


    public function UnitAdd(){
        return view('backend.unit.unit_add');
    } // End Method



     public function UnitStore(Request $request){

        Unit::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Unit Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('unit.all')->with($notification);

    } // End Method


    public function UnitEdit($id){

          $unit = Unit::findOrFail($id);
        return view('backend.unit.unit_edit',compact('unit'));

    }// End Method


    public function UnitUpdate(Request $request){

        $unit_id = $request->id;

        Unit::findOrFail($unit_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Unit Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('unit.all')->with($notification);

    }// End Method


    public function UnitDelete($id){



       $products = Product::where('unit_id',$id);

       if (isset($products) === false) {
        Unit::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Unit Deleted Successfully',
            'alert-type' => 'success'
        );
       }
       else if(isset($products) === true){
        $notification = array(
            'message' => 'Sorry, you can not delete this unit, because the unit its associate with a or any products',
            'alert-type' => 'warning'
        );
        return redirect()->back()->with($notification);
       }



    } // End Method



}
