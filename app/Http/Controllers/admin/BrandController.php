<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::orderBy('name','asc');
        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->get('keyword').'%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brand.list',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'status' => 'required'
        ]);
        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            session()->flash('success', 'Brand created successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Brand created successfully'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::find($id);
        if(empty($brand)){
            return redirect()->route('brands.index');
        }
        return view('admin.brand.edit',compact('brand'));
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
