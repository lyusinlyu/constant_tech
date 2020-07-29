<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $withSubCategories = $request->get('all');
        if ($withSubCategories) {
            return response()->json(Category::whereNull('parent_id')->with('allChildrenCategories')->orderBy('order', 'ASC')->get());
        }
        return response()->json(Category::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['order'] = isset($data['parent_id']) ? Category::find($data['parent_id'])->childrenCategories->count() : Category::whereNull('parent_id')->get()->count();
        Category::create($data);
        return response()->json([
            'statusCode' => 200,
            'message' => 'Category was created successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dataToUpdate = $request->all();
        foreach ($dataToUpdate as $data) {
            Category::where('id', $data['id'])->update([
                'name' => $data['name'],
                'order' => $data['order'],
                'parent_id' => $data['parent_id']
            ]);
        }
        return response()->json([
            'statusCode' => 200,
            'message' => 'Category was updated successfully'
        ]);
    }
}
