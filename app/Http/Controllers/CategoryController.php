<?php

namespace App\Http\Controllers;

use Image;
use Session;
use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // all categories view
    public function index(){
        $categoryes=Category::with('creator_info','editor_info')->get();
        $sl=1;
        return view('dashboard.category.index',compact('categoryes','sl'));
    }

    // categories add view
    public function add(){
        return view('dashboard.category.add');
    }

    // single category to edit
    public function edit($slug){
        $category=Category::where('cat_slug',$slug)->firstOrFail();
        return view('dashboard.category.edit',compact('category'));
    }
    
    // trashed categories view
    public function trashed(){
        $trashed=Category::onlyTrashed()->get();
        $sl=1;
        return view('dashboard.category.trashed',compact('trashed','sl'));
    }

    // category insert
    public function submit(Request $request){
        $this->validate($request,
          [
            'name'=> 'required|max:30|unique:categories,cat_name',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
          ],
          [
            'name.required'=> 'Please insert category name.',
            'name.unique'=>'The name has already been taken.'
          ]);
        $slug = Str::slug($request['name'],'-');
        $creator=Auth::user()->id;
        $insert = Category::insertGetId([
            'cat_name'=>$request['name'],
            'cat_slug'=>$slug,
            'creator'=>$creator,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if ($request->hasFile('image')) {
            $image=$request->file('image');
            $imageName='cat'.$insert.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(256, 200)->save('uploads/'.$imageName);

            Category::where('id',$insert)->update([
                'cat_image'=>'uploads/'.$imageName,
            ]);
        };
        
        if ($insert) {
           Session::flash('success','Successfully added category information.');
           return redirect()->back();
        }else {
           Session::flash('error','Something went wrong');
           return redirect()->back();
        }
    }

    // category update
    public function update(Request $request){
        $id=$request['id'];
        $this->validate($request,
          [
            'name'=> 'required|max:30|unique:categories,cat_name,'.$id.',id',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
          ],
        ['name.required'=> 'Please insert category name.']);
        
        $cat=Category::where('id',$id)->firstOrFail();
        if ($request['image'] != '') {
            if($cat->cat_image != ''){
                unlink(public_path($cat->cat_image));
            }
            if ($request->hasFile('image')) {
                $image=$request->file('image');
                $imageName='cat'.$id.time().'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(256, 200)->save('uploads/'.$imageName);
    
                Category::where('id',$id)->update([
                    'cat_image'=>'uploads/'.$imageName,
                ]);
            };
        }
        $slug = Str::slug($request['name'],'-');
        $editor=Auth::user()->id;
        $update=Category::where('id',$id)->update([
            'cat_name'=>$request['name'],
            'cat_slug'=>$slug,
            'editor'=>$editor,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if ($update) {
           Session::flash('success','Successfully updated category information.');
           return redirect('/dashboard/category/edit/'.$slug);
        }else {
           Session::flash('error','Something went wrong');
           return redirect()->back();
        }
    }

    // softDelete category 
    public function softDelete(Request $request){
        $id=$request['modal_id'];
        $softDelete=Category::where('id',$id)->delete();
        if ($softDelete) {
            Session::flash('success','Successfully deleted category information.');
            return redirect()->back();
         }else {
            Session::flash('error','Something went wrong');
            return redirect()->back();
         }
    }

    // restore category
    public function restore(Request $request){
        $id=$request['modal_id'];
        $restore=Category::onlyTrashed()->where('id',$id)->restore();
        if ($restore) {
            Session::flash('success','Successfully restored category information.');
            return redirect()->back();
         }else {
            Session::flash('error','Something went wrong');
            return redirect()->back();
         }
    }

    // permanently delete category
    public function force_delete(Request $request){
        $id=$request['modal_id'];
        $cat= Category::onlyTrashed()->where('id',$id)->firstOrFail();
        if($cat->cat_image != ''){
            unlink(public_path($cat->cat_image));
        }
        $delete=Category::onlyTrashed()->where('id',$id)->forceDelete();
        if ($delete) {
            Session::flash('success','Successfully deleted category information.');
            return redirect()->back();
         }else {
            Session::flash('error','Something went wrong');
            return redirect()->back();
         }
    }

    // softDelete marked categories
    public function markSoftDelete(Request $request){
        if ($request['check1'] == '') {
            Session::flash('error','Please choose to delete');
            return redirect()->back();
        }else {
            foreach ($request['check1'] as $checked) {
                Category::where('id',$checked)->delete();
            }
            Session::flash('success','Successfully deleted category information.');
            return redirect()->back();
        }
    }

    // permanently delete marked categories
    public function markDelete(Request $request){
        if ($request['check1'] == '') {
            Session::flash('error','Please choose to delete');
            return redirect()->back();
        }else {
            foreach ($request['check1'] as $checked) {
                $cat= Category::onlyTrashed()->where('id',$checked)->firstOrFail();
                if($cat->cat_image != ''){
                    unlink(public_path($cat->cat_image));
                }
                Category::onlyTrashed()->where('id',$checked)->forceDelete();
            }
            Session::flash('success','Successfully deleted category information.');
            return redirect()->back();
        }
    }

}
