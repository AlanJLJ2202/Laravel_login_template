<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    
    public function index()
    {
        try {

            $categories = Category::where("user_id", Auth::user()->id)->get();

            $data = [
                'status' => 'success',
                'categories' => $categories
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            return response()->json($data, 500);
        }
    }

    
    public function create()
    {
        try {
            $category = new Category();
            $category->name = $request->input('name');
            $category->user_id = Auth::user()->id;
            $category->save();

            $data = [
                'status' => 'success'
            ];

            return response()->json($data, 200);

        } catch (\Exception $e) {

            $data = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            return response()->json($data, 500);
        }
    }

    
    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
