<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories= Category::all();
        if(!empty($categories)){
            return response()->json(['categories' => $categories]);
        }
        return response()->json(['message' => 'Empty categories list']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = ['name' => 'required|min:4'];
        if(isset($request->all()['parent_id'])){
            $data['parent_id'] = 'numeric';
        }
        $validated = Validator::make($request->all(), $data);
        if($validated->errors()->count() == 0) {
            $newCategory = Category::query()->create($validated->validated());
            return response()->json(['success' => true, 'data' => $newCategory]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid data']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::query()->find($id);
        if(!is_null($category)){
            return response()->json(['success' => true, 'data' => $category]);
        }
        return response()->json(['success' => false, 'message' => 'Category not found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $category = Category::query()->find($id);
        if(!is_null($category)){
            $data = ['name' => 'required|min:4'];
            if(isset($request->all()['parent_id'])){
                $data['parent_id'] = 'numeric';
            }
            $validated = Validator::make($request->all(), $data);
            if($validated->errors()->count() == 0) {
                Category::query()->find($id)->update($validated->validated());
                return response()->json(['success' => true, 'message' => 'Category successfully updated']);
            }
            return response()->json(['success' => false, 'message' => 'Invalid data']);
        }
        return response()->json(['success' => false, 'message' => 'Category not found']);
    }

    public function getProducts($id){
        $a = Category::query()->where('id', $id)->with('product')->get();
        dd($a);
    }

}
