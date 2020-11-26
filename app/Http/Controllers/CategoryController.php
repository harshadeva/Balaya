<?php

namespace App\Http\Controllers;

use App\Category;
use App\MainCategory;
use App\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *render 'add category' interface
     */
    public function index()
    {
        return view('category.add_category', ['title' =>  __('Add Category')]);
    }

    /**
     *add new category to database
     */
    public function store(Request $request){

        //validation start
        $validator = \Validator::make($request->all(), [
            'newCat' => 'required|max:255',

        ], [
            'newCat.required' => 'New category name should be provided!',
            'newCat.max' => 'New category should be less than 255 characters long!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $exist = Category::where('category',strtoupper($request['newCat']))->first();
        if($exist != null){
            return response()->json(['errors' => ['newCat'=>'Categories already exist!']]);
        }
        //validation end


        //save in category table
        $category = new Category();
        $category->category = strtoupper($request['newCat']);
        $category->status = 1;//default value for active categories
        $category->save();
        //save in category table  end


        return response()->json(['success' => 'Category Saved Successfully!']);

    }

    public function view(Request $request){

        $query = Category::query();
        if (!empty($request['category'])) {
            $query = $query->where('category',  'like', '%' . $request['category'] . '%');
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']. ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $categories = $query->latest()->paginate(10);

        return view('category.view_category', ['title' =>  __('View Category'),'categories' => $categories]);
    }

    public function loadRecent(){
        return  Category::latest()->limit(25)->get();
    }

    public function update(Request $request){
        //validation start
        $validator = \Validator::make($request->all(), [
            'newCat' => 'required|max:255',
            'updateId' => 'required',

        ], [
            'updateId.required' => 'Invalid category!',
            'newCat.required' => 'New category name should be provided!',
            'newCat.max' => 'New category should be less than 255 characters long!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $exist = Category::where('idcategory','=!',$request['updateId'])->where('category',$request['newCat'])->first();
        if($exist != null){
            return response()->json(['errors' => ['newCat'=>'Categories already exist!']]);
        }
        //validation end


        //save in category table
        $category = Category::find(intval($request['updateId']));
        $category->category = strtoupper($request['newCat']);
        $category->save();
        //save in category table  end

        return response()->json(['success' => 'Category updated Successfully!']);

    }

    public function deactivate(Request $request){
        $id = $request['id'];
        $category = Category::find(intval($id));
        if ($category != null) {
            if($category->status == 1){
                $category->status = 0;
                $category->save();
            }

            return response()->json(['success' => 'Category deactivated!']);
        } else {
            return response()->json(['errors' => ['error'=>'Category invalid!']]);

        }
    }

    public function activate(Request $request){
        $id = $request['id'];
        $category = Category::find(intval($id));
        if ($category != null) {
            if($category->status == 0){
                $category->status = 1;
                $category->save();
            }

            return response()->json(['success' => 'Category activated!']);
        } else {
            return response()->json(['errors' => ['error'=>'Category invalid!']]);

        }
    }

}
