<?php
namespace App\Http\Controllers;

use App\Models\Addresses;
use App\Models\Beneficiarie;
use App\Models\Contacts;
use App\Models\Others;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    public function usersAll()
    {
        $users = User::all(); 
        return response()->json($users); 
    }
    
    public function updateStatus(Request $request)
    {   
        User::find($request->id)->update($request->all());  
    
        return response()->json([
            'message' => 'Estado actualizado con éxito!'
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function proceedings($id)
    {
        $user = User::find($id);
        if (!(is_null($user->signature) || $user->signature === 'null')) {
            $user->signature = asset(Storage::url($user->signature));
        }
        $address = Addresses::where('user_id', $id)->first();
        $other = Others::where('user_id', $id)->first();
        $beneficiary = Beneficiarie::where('user_id', $id)->first();
        $contacts = Contacts::where('user_id', $id)->get();
    
        return response()->json([
            'user' => $user,
            'address' => $address,
            'other' => $other,
            'beneficiary' => $beneficiary,
            'contacts' => $contacts
        ], 201);
    }
    public function proceedingsUp(Request $request)
    {
        $id = $request->user['id'];
        $response = $this->proceedings($id); // Obtener la respuesta JSON completa
        $data = $response->getData(); // Extraer el contenido JSON

        if ($data->user) {
            unset($data->user->signature);
            User::find($id)->update($request->user);//Actualizar el usuario
            if ($request->file('signature')){   
                Storage::delete($data->user->signature);        
                $path = $request->file('signature')->store('public/signatures');        
                User::find($id)->update(['signature' => $path]);
                $imagen_rectangular = Image::make($request->file('signature'))->fit(280, 240);
                $imagen_rectangular->save(public_path(Storage::url($path)));
            } 
        }

        if ($data->address) {
            Addresses::where('user_id', $id)->first()->update($request->address);//Actualizar la dirección
        } else {           
            Addresses::create(array_merge(['user_id' => $id], $request->address)); //Crear una nueva dirección
        }

        if ($data->other) {
            Others::where('user_id', $id)->first()->update($request->other);// Actualizar other
        } else {            
            Others::create(array_merge(['user_id' => $id], $request->other));// Crear un nuevo "other" 
        }

        if ($data->beneficiary) {
            Beneficiarie::where('user_id', $id)->first()->update($request->beneficiary);// Actualizar beneficiary
        } else {
            Beneficiarie::create(array_merge(['user_id' => $id], $request->beneficiary));// Crear nuevo Beneficiarie
        }

        return response()->json(['message' => 'Usuario actualizado con éxito!'], 201);
    }

    public function update(Request $request)
    {   
        #Logger($request);
        $data = $request->only(['name', 'a_paterno', 'a_materno', 'alias', 'email', ]);
        $user = User::find($request->id);
        $user->update($data); 

        $request->validate([
            'avatar' => 'required|image|max:3000', // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
        ]);

        if ($request->file('avatar')){
            if ($user->avatar){ Storage::delete($user->avatar); } 

            $path = $request->file('avatar')->store('public/avatars');        
            $user->avatar = $path;
            $user->save();
            $imagen_cuadrada = Image::make($request->file('avatar'))->fit(250);
            $imagen_cuadrada->save(public_path(Storage::url($path)));
        }      

        return response()->json(['message' => 'Perfil actualizado con éxito!'], 201);
    }
    
    public function updateAdmin(Request $request)
    {   
        User::find($request->id)->update($request->all()); 

        return response()->json(['message' => 'Usuario actualizado con éxito!'], 201);
    }

    public function register(Request $request)
    {   
        $type = $request->input('type');
        if ($type == 1) {
            # General
            $request->validate([
                    'name' => 'string|max:50',
                    'a_paterno' => 'string|max:50',
                    'a_materno' => 'string|max:50',
                    'alias' => 'string|max:50',
                    'email' => 'string|email|max:255|unique:users',
                    'password' => 'string|confirmed|min:8',
                ]);    
            $data = $request->only([
                    'name', 'a_paterno', 'a_materno', 'alias',
                ]);
            $data = array_map('strtoupper', $data);
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $data['rol'] = 'Cliente/Externo';
            $user = new User($data);
            $user->save();
        }else{
            #Empleado (muchos campos)
            $request->validate([
                'name' => 'string|max:50',
                'a_paterno' => 'string|max:50',
                'a_materno' => 'string|max:50',
                'alias' => 'string|max:50',
                'email' => 'string|email|max:255|unique:users',
                'password' => 'string|confirmed|min:8',

                'street' => 'string|max:50',
                'suburb' => 'string|max:50',
                'municipality' => 'string|max:50',
                'state' => 'string|max:50',
                'phone' => 'string|size:10|unique:addresses',
                'cell' => 'string|size:10|unique:addresses',

                'state_civil' => 'string|max:20',
                'regimen' => 'string|max:20',
                'sex' => 'string|max:10',
                'birthdate' => 'string|max:10',
                'place_birth' => 'required|string|max:20',
                'nationality' => 'string|max:20',
                'scholarship' => 'string|max:20',
                'title' => 'string',
                'ine' => 'string|max:20',
                'rfc' => 'string|size:13|unique:others',
                'curp' => 'string|size:18|unique:others',
                'socia_health' => 'string|size:11|unique:others',
                'umf' => 'string|max:5',
                'weight' => 'string|max:5',
                'height' => 'string|max:5',
                'blood_type' => 'string|max:4',

                'name_beneficiary' => 'string|max:50',
                'a_paterno_beneficiary' => 'string|max:50',
                'a_materno_beneficiary' => 'string|max:50',
                'relationship' => 'string|max:20',
                'cell_beneficiary' => 'string|size:10',
                'percentage' => 'string|max:4',
                'birthdate_beneficiary' => 'string|max:10',
                'street_beneficiary' => 'string|max:50',
                'suburb_beneficiary' => 'string|max:50',
                'municipality_beneficiary' => 'string|max:20',
                'state_beneficiary' => 'string|max:40',

                'name_contact' => 'string|max:50',
                'a_paterno_contact' => 'string|max:50',
                'a_materno_contact' => 'string|max:50',
                'relationship_contact' => 'string|max:20',
                'cell_contact' => 'string|size:10',
            ]);    

            $general = $request->only([
                    'name', 'a_paterno', 'a_materno', 'alias',
                ]);
            $general = array_map('strtoupper', $general);
            $general['email'] = $request->email;
            $general['password'] = Hash::make($request->password);
            $user = new User($general);
            $user->save();

            $domicilio = array_map('strtoupper', $request->all());
            $domicilio['user_id'] = $user->id;
            $address = new Addresses($domicilio);// INSERTAR INFROMACION DEL USUARIO A TABLA ADDRESSES
            $address->save();

            $otros = array_map('strtoupper', $request->all());
            $otros['user_id'] = $user->id;
            $other = new Others($otros);// INSERTAR INFROMACION DEL USUARIO A TABLA OTHER
            $other->save();

            $beneficiario = array_map('strtoupper', $request->all());
            $beneficiario['user_id'] = $user->id;
            $beneficarie = new Beneficiarie($beneficiario);// INSERTAR INFROMACION DEL USUARIO A TABLA BENEFICARIE
            $beneficarie->save();

            $contacto = array_map('strtoupper', $request->all());
            $contacto['user_id'] = $user->id;
            $contac = new Contacts($contacto);// INSERTAR INFROMACION DEL USUARIO A TABLA CONTACTS
            $contac->save();

            $contacto2['name_contact'] = $request->name_contact2;            
            if ($contacto2['name_contact']) {
                $contacto2['a_paterno_contact'] = $request->a_paterno_contact2;
                $contacto2['a_materno_contact'] = $request->a_materno_contact2;
                $contacto2['relationship_contact'] = $request->relationship_contact2;
                $contacto2['cell_contact'] = $request->cell_contact2;
                $contacto2 = array_map('strtoupper', $contacto2);
                $contacto2['user_id'] = $user->id;
                $contac = new Contacts($contacto2);// INSERTAR INFROMACION DEL USUARIO A TABLA CONTACTS
                $contac->save();// SOLO SE APLICA SI SE LLENAN LOS CAMPOS (NO OBLIGATORIOS)
            }         

            $contacto3['name_contact'] = $request->name_contact3;            
            if ($contacto3['name_contact']) {
                $contacto3['a_paterno_contact'] = $request->a_paterno_contact3;
                $contacto3['a_materno_contact'] = $request->a_materno_contact3;
                $contacto3['relationship_contact'] = $request->relationship_contact3;
                $contacto3['cell_contact'] = $request->cell_contact3;
                $contacto3 = array_map('strtoupper', $contacto3);
                $contacto3['user_id'] = $user->id;
                $contac = new Contacts($contacto3);// INSERTAR INFROMACION DEL USUARIO A TABLA CONTACTS
                $contac->save();// SOLO SE APLICA SI SE LLENAN LOS CAMPOS (NO OBLIGATORIOS)
            }                
        }        

        return response()->json(['message' => 'Usuario registrado con exito'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {            
            $user = Auth::user();
            if($user->active == false){
                return response()->json(['password' => ['Usuario inactivo, contacte a un Administrador']], 401);
            }
            return response()->json([
                'token' => $request->user()->createToken('auth_token')->plainTextToken
            ]);
        }
        throw ValidationException::withMessages([
            'password' => ['Contraseña o correo incorrectos.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tokens Revocados'
        ]);
    }
}