<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $parent_id
     * @return \Illuminate\Http\Response
     */
    public function new(Category $parent)
    {
        if ($parent->depth < 4) {
            $category = Category::create([
                "parent_id" => $parent->id,
                "title" => "New",
                "children" => 0,
                "depth" => $parent->depth + 1
            ]);

            $parent->children = 1;
            $parent->save();

            $result = [
                "success" => true,
                "data" => $category
            ];
        } else {
            $result = [
                "success" => false
            ];
        }

        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->title = $request->input('title');
        $category->save();

        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json($category, 200);
    }

    /**
     * Display a listing of the Categoies.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories(Request $request)
    {
        $parent_id = $request->input('parent');

        if ($parent_id == '#') {
            $parent_id = 0;
        }

        $children = Category::where('parent_id', $parent_id)->get();

        $result = [];
        foreach ($children as $child) {
            $data = array(
                'id' => $child->id,
                'text' => $child->title,
                'icon' => "fa fa-folder icon-lg kt-font-danger",
                "children" => (bool)$child->children,
                "type" => "root"
            );
            array_push($result, $data);
        }

        return response()->json($result, 200);
    }
}
