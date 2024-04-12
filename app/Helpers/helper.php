<?php

use App\Models\Category;

function get_categories()
{
    return Category::orderBy('name','asc')
                    ->with('sub_categories')
                    ->where('show_on_home','yes')
                    ->where('status',1)
                    ->get();
}

