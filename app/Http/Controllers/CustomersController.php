<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\CECOs;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customers::all(); 
        return response()->json($customers); 
    }

    public function create(Request $request)
    {
        // Validar campos únicos antes de crear el cliente
        $validatedData = $request->validate([
            'name' => 'unique:customers,name,NULL,id,prefijo,' . $request->prefijo,
            'prefijo' => 'unique:customers,prefijo,NULL,id,name,' . $request->name,
        ]);

        $customer = new Customers($validatedData);
        $customer->save();
        
        //SELECCIONAMOS EL CLIENTE SI LO ENCUENTRA, LE AGREGAMOS EL ID A LOS CECOs
        $customer = DB::table('customers')->where('name', $request->name)->where('prefijo', $request->prefijo)->first();
        if ($customer) {
            $cecos = DB::table('c_e_c_os')->where('customer', null)->get();
            foreach ($cecos as $ceco) {
                //Agregar ID
                CECOs::find($ceco->id)->update(['customer'=> $customer->id]);
            }
        }
        return response()->json([
            'message' => 'Cliente registrado con exito'
        ], 201);
    }

    public function store(Request $request)
    {
        //
    }

    public function addceco(Request $request)
    {
        // Validar campo único antes de crear el CECO
        $validatedData = $request->validate([
            'description' => 'unique:c_e_c_os,description',
        ]);
        $ceco = new CECOs($validatedData);
        $ceco->save();
        return response()->json([
            'message' => 'CECO registrado con exito'
        ], 201);
    } 

    public function cecosR()
    {
        $cecos = DB::table('c_e_c_os')->where('customer', null)->get();
        return $cecos;
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
