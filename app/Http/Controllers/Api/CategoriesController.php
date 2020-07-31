<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Auth;

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
        $categoryId = $request->get('id');
        if ($withSubCategories) {
            if ($categoryId) {
                $query = Category::where('id', $categoryId);
            } else {
                $query = Category::whereNull('parent_id');
            }
            $response = $query->where('user_id', Auth::id())->with('allChildrenCategories')->orderBy('order', 'ASC')->get();
            return response()->json($response);
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
        $data = $request->validate([
            'name' => 'bail|required|max:255',
            'parent_id' => ''
        ]);
        $data['user_id'] = Auth::id();
        $data['order'] = isset($data['parent_id']) ? Category::find($data['parent_id'])->childrenCategories->count() : Category::whereNull('parent_id')->get()->count();
        $category = Category::create($data);
        return response()->json([
            'statusCode' => 200,
            'message' => 'Category was created successfully',
            'data' => $category
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
            if (!isset($data['name'])) {
                return response()->json([
                    'statusCode' => 422,
                    'message' => 'Name is required'
                ]);
            }
            Category::where('id', $data['id'])->update([
                'name' => $data['name'],
                'order' => $data['order'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
            ]);
        }
        return response()->json([
            'statusCode' => 200,
            'message' => 'Category was updated successfully'
        ]);
    }
}
