<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryServices;

class CategoryController extends Controller
{
    private $category=null;

    public function __construct(CategoryServices $categoryService)
    {
        $this->category = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->category->getCategory();
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = $request->validate([
            "name"=>"required|string|max:255",
            "description"=>"required|string|max:500",
            "image" => "required"
        ]);
        $this->category->addCategory($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
