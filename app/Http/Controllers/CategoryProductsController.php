<?php

namespace App\Http\Controllers;

use App\Models\CategoryProducts;
use Illuminate\Http\Request;

class CategoryProductsController extends Controller
{
    public function index()
    {
        $categories = CategoryProducts::all(); 
        return response()->json($categories); 
    }

    public function store(Request $request)
    {
        $category = new CategoryProducts($request->all());

        $category->save();
        return response()->json(['message' => 'Categoria registrada con exito'], 201);
    }

    public function show($id)
    {
        $category = CategoryProducts::find($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        // Find the category by ID, or return a 404 error if not found
        $category = CategoryProducts::findOrFail($id);

        // Update the category with the provided data
        $category->update($request->all());
        
        return response()->json(['message' => 'Categoria actualizada exitosamente.'], 201);
    }

    public function destroy($id)
    {
        CategoryProducts::destroy($id);

        return response()->json(['message' => 'Categoria eliminada exitosamente.'], 201);
    }
}
