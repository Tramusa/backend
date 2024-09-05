<?php

namespace App\Http\Controllers;

use App\Models\DetailsRequisitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailsRequisitionsController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos del request
        $validatedData = $request->validate([
            'id_product' => 'required|integer|exists:products_services,id', // Validar que el producto exista
            'name' => 'required|string', // Nombre del producto
            'price' => 'required|numeric', // Precio del producto
            'isr' => 'nullable|numeric', // ISR del producto
            'iva' => 'required|numeric', // IVA del producto
            'ret_iva' => 'nullable|numeric', // Retención de IVA del producto
            'ret_ish' => 'nullable|numeric', // Retención de ISH del producto
            'unit_measure' => 'required|string', // Unidad de medida del producto
            'cantidad' => 'required|integer|min:1', // Cantidad debe ser mayor que 0
            'justific' => 'nullable|string', // Justificación es opcional
            'id_requisition' => 'nullable|integer|exists:requisitions,id', // Validar si id_requisition es proporcionado
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Crear una nueva instancia de DetailsRequisitions con los datos validados
        $requisition = new DetailsRequisitions([
            'id_product' => $validatedData['id_product'],
            'name' => $validatedData['name'],  // Asigna el nombre del producto
            'price' => $validatedData['price'],  // Asigna el precio del producto
            'isr' => $validatedData['isr'],  // Asigna el ISR del producto
            'iva' => $validatedData['iva'],  // Asigna el IVA del producto
            'ret_iva' => $validatedData['ret_iva'],  // Asigna la retención de IVA del producto
            'ret_ish' => $validatedData['ret_ish'],  // Asigna la retención de ISH del producto
            'unit_measure' => $validatedData['unit_measure'],  // Asigna la unidad de medida del producto
            'cantidad' => $validatedData['cantidad'],  // Asigna la cantidad
            'justific' => $validatedData['justific'] ?? null,  // Asigna la justificación si existe, de lo contrario null
            'id_user' => $user->id,  // Asigna el ID del usuario
            'id_requisition' => $validatedData['id_requisition'] ?? 0,  // Asigna el ID de la requisición si se proporciona, de lo contrario 0
        ]);

        // Guardar el nuevo registro en la base de datos
        $requisition->save();

        // Devolver una respuesta JSON con un mensaje de éxito
        return response()->json(['message' => 'Producto o servicio agregado con éxito'], 201);
    }
    public function show($id)
    {        
        $user = Auth::user();// Get the logged-in user

        // Fetch details requisitions for the logged-in user where id_product is not 0
        $detailsRequisitions = DetailsRequisitions::where('id_user', $user->id)
                                                  ->where('id_requisition', $id)
                                                  ->get();

        return response()->json($detailsRequisitions);
    }

    public function destroy($id)
    {
        DetailsRequisitions::destroy($id);

        return response()->json(['message' => 'Producto o servicio eliminado exitosamente.'], 201);
    }
}
