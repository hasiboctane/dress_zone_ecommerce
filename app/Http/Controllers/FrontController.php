<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $featured_products  = Product::orderBy('title','asc')
                                        ->take(8)
                                        ->where('is_featured','yes')
                                        ->where('status',1)
                                        ->get();
        $latest_products  = Product::orderBy('created_at','desc')
                                        ->take(8)
                                        ->where('status',1)
                                        ->get();
        return view('frontend.home',compact('featured_products','latest_products'));
    }
}
