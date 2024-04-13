<?php

use App\Models\Category;

function get_categories()
{
    return Category::orderBy('name','desc')
                    ->with('sub_categories')
                    ->with('products')
                    ->where('show_on_home','yes')
                    ->where('status',1)
                    ->get();
}


