<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = SubCategory::latest('id')->join('categories','sub_categories.category_id','=','categories.id')->select('sub_categories.*','categories.name as category_name');
        if(!empty(request()->get('keyword'))){
            $subCategories = $subCategories->where('name','like','%'.request()->get('keyword').'%');
        }
        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list',compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name','asc')->get();
        return view('admin.sub_category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required || unique:sub_categories',
            'category' => 'required'
        ]);
        if ($validator->passes() ) {
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();
            session()->flash('success', 'Sub-Category created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sub-Category created successfully',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        if(empty($subCategory)){
            return redirect()->route('sub-categories.index');
        }
        return view('admin.sub_category.edit', compact('subCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
