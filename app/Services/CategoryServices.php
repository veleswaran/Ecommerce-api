<?php

namespace App\Services;

use App\Models\Category;

class CategoryServices{
    private $category=null;

    function __construct(Category $category){
        $this->category=$category;
    }

    function getCategory(){
        return $this->category->get();
    }

    function addCategory($category){
        $this->category->create($category);
    }
}