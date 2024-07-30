<?php

namespace App\Http\Controllers;

use App\Models\ProductsServices;
use Illuminate\Http\Request;

class ProductsServicesController extends Controller
{
    public function index()
    {
        $products = ProductsServices::all(); 
        return response()->json($products); 
    }

    public function store(Request $request)
    {
        $product = new ProductsServices($request->all());

        $product->save();
        return response()->json(['message' => 'Producto o Servicio registrado con exito'], 201);
    }

    public function show($id)
    {
        $product = ProductsServices::find($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        // Find the product by ID, or return a 404 error if not found
        $product = ProductsServices::findOrFail($id);

        // Update the product with the provided data
        $product->update($request->all());
        
        return response()->json(['message' => 'Producto o Servicio actualizado exitosamente.'], 201);
    }

    public function destroy($id)
    {
        ProductsServices::destroy($id);

        return response()->json(['message' => 'Producto o Servicio eliminado exitosamente.'], 201);
    }
}