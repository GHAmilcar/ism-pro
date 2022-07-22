<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth as Auth;

class CategoryController extends Controller
{
    public function CategoryAll(){

        $categories = Category::latest()->get();
        return view('backend.category.category_all')->with([
            'categories' => $categories
        ]);

    } // End Mehtod

    public function CategoryAdd(){
     return view('backend.category.category_add');
    } // End Mehtod


    public function CategoryStore(Request $request){

        Category::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);

    } // End Method

     public function CategoryEdit($id){

          $category = Category::findOrFail($id);
        return view('backend.category.category_edit',compact('category'));

    }// End Method


     public function CategoryUpdate(Request $request){

        $category_id = $request->id;

        Category::findOrFail($category_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);

    }// End Method


    public function CategoryDelete($id){



       $products = Product::where('category_id',$id)->first();
       if (isset($products) === false) {
            Category::findOrFail($id)->delete();
            $notification = array(
                'message' => 'Category Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
       }
       else if(isset($products) === true){
        $notification = array(
            'message' => 'You have products registers with this category, can not be deleted',
            'alert-type' => 'warning'
        );
        return redirect()->back()->with($notification);
       }


    } // End Method


}
