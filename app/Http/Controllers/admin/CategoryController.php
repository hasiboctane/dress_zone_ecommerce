<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::latest('id');
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);
        return view('admin.category.category-list', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }else{
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->show_on_home = $request->show_on_home;
            $category->save();
            // save image
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $imageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$imageName;
                File::copy($sPath,$dPath);
                // Generate Thumbnail
                $thumbPath = public_path().'/uploads/category/thumb/'.$imageName;
                $manager = ImageManager::gd();
                $image = $manager->read($sPath);
                $image->resize(400,500);
                $image->save($thumbPath);

                // save to database
                $category->image= $imageName;
                $category->save();
                // File::delete($sPath);


            }
            session()->flash('success', 'Category created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
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
        $category = Category::findOrFail($id);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if(empty($category)){
            session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message'=> 'Category not found',
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required || unique:categories,slug,'.$category->id.',id',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }else{
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->show_on_home = $request->show_on_home;
            $category->save();
            $oldImage = $category->image;
            // save image
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $imageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$imageName;
                File::copy($sPath,$dPath);
                // Generate Thumbnail
                $thumbPath = public_path().'/uploads/category/thumb/'.$imageName;
                $manager = ImageManager::gd();
                $image = $manager->read($sPath);
                $image->resize(400,500);
                $image->save($thumbPath);

                // save to database
                $category->image= $imageName;
                $category->save();
                // delete old image
                File::delete(public_path().'/uploads/category/'.$oldImage);
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);


            }
            session()->flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if(empty($category)){
            session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                // 'notFound' => true,
                'message'=> 'Category not found',
            ]);
            // return redirect()->route('categories.index');
        }
        File::delete(public_path().'/uploads/category/'.$category->image);
        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        $category->delete();
        session()->flash('success', 'Category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
