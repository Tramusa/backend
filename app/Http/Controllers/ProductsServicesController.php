<?php

namespace App\Http\Controllers;

use App\Models\ProductsServices;
use Illuminate\Http\Request;

class ProductsServicesController extends Controller
{
    public function index()
    {
        $products = ProductsServices::with('categoryProduct')->get();

        $products->each->append('net_cost');

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = new ProductsServices($request->all());
        $product->save();

        $product->load('categoryProduct');
        $product->append('net_cost');

        return response()->json([
            'message' => 'Producto o Servicio registrado con exito',
            'product' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = ProductsServices::with('categoryProduct')->findOrFail($id);

        $product->append('net_cost');

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = ProductsServices::findOrFail($id);

        $product->update($request->all());
        $product->refresh();

        $product->load('categoryProduct');
        $product->append('net_cost');

        return response()->json([
            'message' => 'Producto o Servicio actualizado exitosamente.',
            'product' => $product
        ], 200);
    }

    public function destroy($id)
    {
        ProductsServices::destroy($id);

        return response()->json([
            'message' => 'Producto o Servicio eliminado exitosamente.'
        ], 200);
    }

    public function searchQuery(Request $request)
    {
        $query = $request->input('name');
        $idProduct = $request->input('id_product');

        $products = ProductsServices::with('categoryProduct')
            ->when($query || $idProduct, function ($q) use ($query, $idProduct) {
                $q->where(function ($subQuery) use ($query, $idProduct) {
                    if ($query) {
                        $subQuery->where('name', 'like', '%' . $query . '%');
                    }

                    if ($idProduct) {
                        $method = $query ? 'orWhere' : 'where';
                        $subQuery->{$method}('id', 'like', '%' . $idProduct . '%');
                    }
                });
            })
            ->take(14)
            ->get();

        $products->each->append('net_cost');

        return response()->json($products);
    }
}