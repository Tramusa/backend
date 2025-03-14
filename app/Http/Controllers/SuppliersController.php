<?php

namespace App\Http\Controllers;

use App\Models\SupplierBanck;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::with('bankDetails')
            ->orderBy('business_name', 'asc') // Orden ascendente por 'business_name'
            ->get();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        // Validar que el RFC no exista en otro proveedor
        $request->validate([
            'RFC' => 'required|unique:suppliers,RFC',
        ]);

        $supplier = new Suppliers($request->all());
        $supplier->save();

        // Actualizar los bancos con id_supplier = 0
        SupplierBanck::where('id_supplier', 0)
            ->update(['id_supplier' => $supplier->id]);

        return response()->json(['message' => 'Proveedor registrado con éxito'], 201);
    }

    public function show($id)
    {
        $supplier = Suppliers::find($id);
        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        // Validar que el RFC no esté duplicado en otro proveedor (excluyendo el actual)
        $request->validate([
            'RFC' => 'required|unique:suppliers,RFC,' . $id,
        ]);
        
        // Find the supplier by ID, or return a 404 error if not found
        $supplier = Suppliers::findOrFail($id);

        // Update the supplier with the provided data
        $supplier->update($request->all());
        
        return response()->json(['message' => 'Proveedor actualizado exitosamente.'], 201);
    }

    public function searchQuery(Request $request)
    {
        $query = $request->input('supplier');

        $suppliers = Suppliers::query()
            ->select('id', 'business_name', 'payment_method') // Solo selecciona los campos necesarios
            ->when($query, function ($q) use ($query) {
                $q->where('business_name', 'like', '%' . $query . '%')
                ->orWhere('tradename', 'like', '%' . $query . '%');
            })
            ->take(8)
            ->get();

        return response()->json($suppliers);
    }

    public function destroy($id)
    {
        Suppliers::destroy($id);

        return response()->json(['message' => 'Proveedor eliminado exitosamente.'], 201);
    }
}