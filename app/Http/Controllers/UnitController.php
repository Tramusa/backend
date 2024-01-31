<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Tractocamiones;
use App\Models\Dollys;
use App\Models\Maquinarias;
use App\Models\Remolques;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tortons;
use App\Models\UnitsPDFs;
use App\Models\Utilitarios;
use App\Models\Volteos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UnitController extends Controller
{
    public function units($type)
    {
        switch ($type) {
            case 1:
                $units = Tractocamiones::all();
                return response()->json($units);
            case 2:
                $units = Remolques::all();
                return response()->json($units);
            case 3:
                $units = Dollys::all(); 
                return response()->json($units);
            case 4:
                $units = Volteos::all(); 
                return response()->json($units);
            case 5:
                $units = Toneles::all(); 
                return response()->json($units);
            case 6:
                $units = Tortons::all(); 
                return response()->json($units);
            case 7:
                $units = Autobuses::all(); 
                return response()->json($units);
            case 8:
                $units = Sprinters::all(); 
                return response()->json($units);
            case 9:
                $units = Utilitarios::all(); 
                return response()->json($units);
            case 10:
                $units = Maquinarias::all(); 
                return response()->json($units);
            default:
                break;
        }
    }
    
    public function create(Request $request)
    {      
        $request->validate([ 'tipo' => 'required']);

        switch ($request->tipo) {
            case 1:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'year', 'no_motor', 'no_seriously', 'motor', 'color', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'cre', 'expiration_cre', 'engine_capacity', 'speeds', 'differential_pitch', 'transmission', 'ecm', 'esn', 'cpl', 'extent_tire', 'tire', 'ejes', 'carrier', 'user']);
                
                $unit = new Tractocamiones($data);                
                $unit->save();

                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');        
                    $unit->front = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    $unit->rear = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    $unit->left = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    $unit->right = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                break;
            case 2:
                $unit = new Remolques($request->all());
                $unit->save();
                break;
            case 3:
                $unit = new Dollys($request->all());                
                $unit->save();
                break;
            case 4:
                $unit = new Volteos($request->all());                
                $unit->save();
                break;
            case 5:
                $unit = new Toneles($request->all());                
                $unit->save();
                break;
            case 6:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'engine_capacity', 'speeds', 'differential_pitch', 'transmission', 'ecm', 'esn', 'cpl', 'extent_tire', 'tire', 'ejes', 'no_passengers', 'user']);
                
                $unit = new Tortons($data);                
                $unit->save();

                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');        
                    $unit->front = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    $unit->rear = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    $unit->left = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    $unit->right = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                break;
            case 7:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'no_passengers', 'user']);
            
                $unit = new Autobuses($data);                
                $unit->save();
                
                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');        
                    $unit->front = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    $unit->rear = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    $unit->left = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    $unit->right = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }                 
                break;
            case 8:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'no_passengers', 'user']);
            
                $unit = new Sprinters($data);                
                $unit->save();
                
                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');        
                    $unit->front = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    $unit->rear = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    $unit->left = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    $unit->right = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }   
                break;
            case 9:
                Logger($request->all()) ;  

                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'user']);
                
                $unit = new Utilitarios($data);            
                $unit->save();
                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');        
                    $unit->front = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    $unit->rear = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    $unit->left = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    $unit->right = $path;
                    $unit->save();
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                break;
            case 10:
                $unit = new Maquinarias($request->all());                
                $unit->save();
                break;
            default:
                break;
        }
        return response()->json(['message' => 'Unidad registrada con exito'], 201);             
       
    }

    public function show(Request $request)
    { 
        $docs = DB::table('units_p_d_fs')->where('unit_id', $request->id)->where('type_unit', $request->type)->get();
        foreach ($docs as $doc) {
            $doc->location = asset(Storage::url($doc->location));
        }
        return response()->json($docs);            
    }

    public function unit(Request $request)
    {      
        $request->validate([ 'type' => 'required', ]);

        switch ($request->type) {
            case 1:
                $unit = Tractocamiones::find($request->id);
                $front = $unit->front; 
                unset($unit->front);
                if ($front) {  $unit->front_img = asset(Storage::url($front));  }
                $rear = $unit->rear;
                unset($unit->rear);
                if ($rear) {  $unit->rear_img = asset(Storage::url($rear));  }
                $left = $unit->left;
                unset($unit->left);
                if ($left) {  $unit->left_img = asset(Storage::url($left));  }
                $right = $unit->right;
                unset($unit->right);
                if ($right) {  $unit->right_img = asset(Storage::url($right));  }
                return $unit;
                return $unit;
                break;
            case 2:
                $unit = Remolques::find($request->id);
                return $unit;
                break;
            case 3:
                $unit = Dollys::find($request->id);
                return $unit;
                break;
            case 4:
                $unit = Volteos::find($request->id); 
                return $unit;
                break;
            case 5:
                $unit = Toneles::find($request->id); 
                return $unit;
                break;
            case 6:
                $unit = Tortons::find($request->id); 
                $front = $unit->front; 
                unset($unit->front);
                if ($front) {  $unit->front_img = asset(Storage::url($front));  }
                $rear = $unit->rear;
                unset($unit->rear);
                if ($rear) {  $unit->rear_img = asset(Storage::url($rear));  }
                $left = $unit->left;
                unset($unit->left);
                if ($left) {  $unit->left_img = asset(Storage::url($left));  }
                $right = $unit->right;
                unset($unit->right);
                if ($right) {  $unit->right_img = asset(Storage::url($right));  }
                return $unit;
                break;
            case 7:
                $unit = Autobuses::find($request->id); 
                $front = $unit->front; 
                unset($unit->front);
                if ($front) {  $unit->front_img = asset(Storage::url($front));  }
                $rear = $unit->rear;
                unset($unit->rear);
                if ($rear) {  $unit->rear_img = asset(Storage::url($rear));  }
                $left = $unit->left;
                unset($unit->left);
                if ($left) {  $unit->left_img = asset(Storage::url($left));  }
                $right = $unit->right;
                unset($unit->right);
                if ($right) {  $unit->right_img = asset(Storage::url($right));  }
                return $unit;
                break;
            case 8:
                $unit = Sprinters::find($request->id); 
                $front = $unit->front;
                unset($unit->front);
                if ($front) {  $unit->front_img = asset(Storage::url($front));  }
                $rear = $unit->rear;
                unset($unit->rear);
                if ($rear) {  $unit->rear_img = asset(Storage::url($rear));  }
                $left = $unit->left;
                unset($unit->left);
                if ($left) {  $unit->left_img = asset(Storage::url($left));  }
                $right = $unit->right;
                unset($unit->right);
                if ($right) {  $unit->right_img = asset(Storage::url($right));  }
                return $unit;
                break;
            case 9:
                $unit = Utilitarios::find($request->id); 
                $front = $unit->front;
                unset($unit->front);
                if ($front) {  $unit->front_img = asset(Storage::url($front));  }
                $rear = $unit->rear;
                unset($unit->rear);
                if ($rear) {  $unit->rear_img = asset(Storage::url($rear));  }
                $left = $unit->left;
                unset($unit->left);
                if ($left) {  $unit->left_img = asset(Storage::url($left));  }
                $right = $unit->right;
                unset($unit->right);
                if ($right) {  $unit->right_img = asset(Storage::url($right));  }
                return $unit;
                break;
            case 10:
                $unit = Maquinarias::find($request->id); 
                return $unit;
                break;
            default:
                break;
        }           
    }

    public function update(Request $request, $type)
    {   
        switch ($type) {
            case 1:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'year', 'no_motor', 'no_seriously', 'motor', 'color', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'cre', 'expiration_cre', 'engine_capacity', 'speeds', 'differential_pitch', 'transmission', 'ecm', 'esn', 'cpl', 'extent_tire', 'tire', 'ejes', 'carrier', 'odometro', 'user']);
                
                Tractocamiones::find($request->id)->update($data);
                // Obtén el registro actual
                $tractocamion = Tractocamiones::find($request->id);

                // Elimina las imágenes antiguas si se proporcionan nuevas imágenes
                if ($request->hasFile('front')) {
                    Storage::delete($tractocamion->front);
                }
                if ($request->hasFile('rear')) {
                    Storage::delete($tractocamion->rear);
                }
                if ($request->hasFile('left')) {
                    Storage::delete($tractocamion->left);
                }
                if ($request->hasFile('right')) {
                    Storage::delete($tractocamion->right);
                }

                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');
                    Tractocamiones::find($request->id)->update(['front' => $path]);
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    Tractocamiones::find($request->id)->update(['rear' => $path]);;
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    Tractocamiones::find($request->id)->update(['left' => $path]);
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    Tractocamiones::find($request->id)->update(['right' => $path]);
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }   
                break;
            case 2:       
                Remolques::find($request->id)->update($request->all());  
                break;
            case 3:    
                Dollys::find($request->id)->update($request->all());
                break;
            case 4:       
                Volteos::find($request->id)->update($request->all());
                break;
            case 5:       
                Toneles::find($request->id)->update($request->all());
                break;
            case 6:   
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'engine_capacity', 'speeds', 'differential_pitch', 'transmission', 'ecm', 'esn', 'cpl', 'extent_tire', 'tire', 'ejes', 'no_passengers', 'user']);
                
                Tortons::find($request->id)->update($data);

                // Obtén el registro actual
                $torton = Tortons::find($request->id);

                // Elimina las imágenes antiguas si se proporcionan nuevas imágenes
                if ($request->hasFile('front')) {
                    Storage::delete($torton->front);
                }
                if ($request->hasFile('rear')) {
                    Storage::delete($torton->rear);
                }
                if ($request->hasFile('left')) {
                    Storage::delete($torton->left);
                }
                if ($request->hasFile('right')) {
                    Storage::delete($torton->right);
                }

                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');
                    Tortons::find($request->id)->update(['front' => $path]);
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    Tortons::find($request->id)->update(['rear' => $path]);;
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    Tortons::find($request->id)->update(['left' => $path]);
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    Tortons::find($request->id)->update(['right' => $path]);
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }  
                break;
            case 7:   
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'no_passengers', 'odometro', 'user']);
            
                Autobuses::find($request->id)->update($data); 

                // Obtén el registro actual
                $autobus = Autobuses::find($request->id);

                // Elimina las imágenes antiguas si se proporcionan nuevas imágenes
                if ($request->hasFile('front')) {
                    Storage::delete($autobus->front);
                }
                if ($request->hasFile('rear')) {
                    Storage::delete($autobus->rear);
                }
                if ($request->hasFile('left')) {
                    Storage::delete($autobus->left);
                }
                if ($request->hasFile('right')) {
                    Storage::delete($autobus->right);
                }
                
                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');
                    Autobuses::find($request->id)->update(['front' => $path]);
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    Autobuses::find($request->id)->update(['rear' => $path]);;
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    Autobuses::find($request->id)->update(['left' => $path]);
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    Autobuses::find($request->id)->update(['right' => $path]);
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }       
                break;
            case 8:
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'no_passengers', 'odometro', 'user']);
            
                Sprinters::find($request->id)->update($data); 

                // Obtén el registro actual
                $sprinter = Sprinters::find($request->id);

                // Elimina las imágenes antiguas si se proporcionan nuevas imágenes
                if ($request->hasFile('front')) {
                    Storage::delete($sprinter->front);
                }
                if ($request->hasFile('rear')) {
                    Storage::delete($sprinter->rear);
                }
                if ($request->hasFile('left')) {
                    Storage::delete($sprinter->left);
                }
                if ($request->hasFile('right')) {
                    Storage::delete($sprinter->right);
                }
                
                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');
                    Sprinters::find($request->id)->update(['front' => $path]);
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    Sprinters::find($request->id)->update(['rear' => $path]);;
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    Sprinters::find($request->id)->update(['left' => $path]);
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    Sprinters::find($request->id)->update(['right' => $path]);
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }         
                break;
            case 9:  
                $request->validate(['front' => 'image|max:3000', 'rear' => 'image|max:3000', 'left' => 'image|max:3000', 'right' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
                $data = $request->only(['no_economic', 'brand', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'ejes', 'odometro', 'user']);
                 
                Utilitarios::find($request->id)->update($data);

                // Obtén el registro actual
                $utilitario = Utilitarios::find($request->id);

                // Elimina las imágenes antiguas si se proporcionan nuevas imágenes
                if ($request->hasFile('front')) {
                    Storage::delete($utilitario->front);
                }
                if ($request->hasFile('rear')) {
                    Storage::delete($utilitario->rear);
                }
                if ($request->hasFile('left')) {
                    Storage::delete($utilitario->left);
                }
                if ($request->hasFile('right')) {
                    Storage::delete($utilitario->right);
                }

                if ($request->file('front')){            
                    $path = $request->file('front')->store('public/units');
                    Utilitarios::find($request->id)->update(['front' => $path]);
                    $imagen_rectangular = Image::make($request->file('front'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                }
                if ($request->file('rear')){            
                    $path = $request->file('rear')->store('public/units');        
                    Utilitarios::find($request->id)->update(['rear' => $path]);;
                    $imagen_rectangular = Image::make($request->file('rear'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('left')){            
                    $path = $request->file('left')->store('public/units');        
                    Utilitarios::find($request->id)->update(['left' => $path]);
                    $imagen_rectangular = Image::make($request->file('left'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                if ($request->file('right')){            
                    $path = $request->file('right')->store('public/units');        
                    Utilitarios::find($request->id)->update(['right' => $path]);
                    $imagen_rectangular = Image::make($request->file('right'))->fit(280, 250);
                    $imagen_rectangular->save(public_path(Storage::url($path)));
                } 
                break;
            case 10:       
                Maquinarias::find($request->id)->update($request->all());
                break;
            default:
                break;
        } 
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');    
        switch ($type) {
            case 1:
                Tractocamiones::find($id)->delete();
                break;
            case 2:
                Remolques::find($id)->delete();
                break;
            case 3:
                Dollys::find($id)->delete();
                break;
            case 4:
                Volteos::find($id)->delete();
                break;
            case 5:
                Toneles::find($id)->delete();
                break;
            case 6:
                Tortons::find($id)->delete();
                break;
            case 7:
                Autobuses::find($id)->delete();
                break;
            case 8:
                Sprinters::find($id)->delete();
                break;
            case 9:
                Utilitarios::find($id)->delete();
                break;
            case 10:
                Maquinarias::find($id)->delete();
                break;
            default:
                return response()->json(['message' => 'Tipo de unidad no válido'], 400);
        }    
        return response()->json(['message' => 'Unidad eliminada']);
    }

    public function destroyDocs($id)
    {
        $unit = UnitsPDFs::find($id);
        if ($unit->location){ Storage::delete($unit->location); }
        $unit->delete();
        return response()->json(['message' => 'Documento eliminado exitosamente.']);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'unit_id' => 'required',
            'pdf' => 'required|file|mimes:pdf'
        ]);

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('public/pdfs');  
            
            // Guardar el título y el ID de la unidad
            $title = $request->input('title');
            $unitId = $request->input('unit_id');
            $unitType = $request->input('type_unit');
            
            // Crear un nuevo registro en la tabla "pdfs"
            UnitsPDFs::create([
                'title' => $title,
                'unit_id' => $unitId,
                'type_unit' => $unitType,
                'location' => $path
            ]);
            
            return response()->json(['message' => 'Archivo PDF subido correctamente']);
        }
        
        return response()->json(['error' => 'No se encontró ningún archivo PDF']);
    }
}
