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
        $data = $request->all();
        $data['order'] = isset($data['parent_id']) ? Category::find($data['parent_id'])->childrenCategories->count() : Category::whereNull('parent_id')->get()->count();
        return response()->json(Category::create($data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return response()->json(Category::whereNull('parent_id')->with('allChildrenCategories')->get());
    }
}
