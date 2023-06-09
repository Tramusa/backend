<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\EarringsController;
use App\Http\Controllers\InspectionsController;
use App\Http\Controllers\PeajesController;
use App\Http\Controllers\PointsInterest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\DB;





Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users', [AuthController::class, 'usersAll']);
    Route::put('/updateStatus', [AuthController::class, 'updateStatus']);
    Route::get('/user/{id}', [AuthController::class, 'show']);
    Route::post('/userAdmin', [AuthController::class, 'updateAdmin']);
});

Route::middleware('auth:sanctum')->post('/change-password', [ProfileController::class, 'changePassword']);
Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show']);
Route::middleware('auth:sanctum')->post('/user', [AuthController::class, 'update']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/createUnit', [UnitController::class, 'create']);
Route::middleware('auth:sanctum')->post('/unit', [UnitController::class, 'unit']);
Route::middleware('auth:sanctum')->get('/units/{type}', [UnitController::class, 'units']);
Route::middleware('auth:sanctum')->put('/unit/{type}', [UnitController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/unit', [UnitController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/upload-pdf', [UnitController::class, 'upload']);
Route::middleware('auth:sanctum')->post('/docsUnits', [UnitController::class, 'show']);
Route::middleware('auth:sanctum')->delete('/docsUnits/{id}', [UnitController::class, 'destroyDocs']);

Route::middleware('auth:sanctum')->post('/createTrip', [TripController::class, 'create']);
Route::middleware('auth:sanctum')->get('/operators', [TripController::class, 'operatorsAll']);
Route::middleware('auth:sanctum')->get('/search', [TripController::class, 'search']);
Route::middleware('auth:sanctum')->post('/addUnit', [TripController::class, 'addUnit']);
Route::middleware('auth:sanctum')->get('/unitsTrip/{trip}', [TripController::class, 'show']);
Route::middleware('auth:sanctum')->delete('/deleteUnit/{id}', [TripController::class, 'deleteUnit']);
Route::middleware('auth:sanctum')->put('/createTrip/{trip}', [TripController::class, 'update']);
Route::middleware('auth:sanctum')->get('/trips/{type}', [TripController::class, 'showTrips']);
Route::middleware('auth:sanctum')->get('/cancelTrip/{trip}', [TripController::class, 'cancel']);
Route::middleware('auth:sanctum')->get('/finishTrip/{trip}', [TripController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/trip/{id}', [TripController::class, 'showTrip']);

Route::middleware('auth:sanctum')->get('/tripsCount', function () {
    $trips = DB::table('trips')->where('status', 1)->get();
    return response()->json(['total' => count($trips)]);
});
Route::middleware('auth:sanctum')->get('/inspectionCount/{user}', function ($user) {
    $inspections = DB::table('inspections')->where('status', 1)->where('responsible', $user)->get();
    return response()->json(['total' => count($inspections)]);
});
Route::middleware('auth:sanctum')->get('/earringsCount', function () {
    $earrings = DB::table('earrings')->where('status', 1)->get();
    return response()->json(['total' => count($earrings)]);
});

Route::middleware('auth:sanctum')->get('/programs', [ProgramsController::class, 'index']);
Route::middleware('auth:sanctum')->post('/addMto', [ProgramsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/unitsMto/{type}', [ProgramsController::class, 'units']);
Route::middleware('auth:sanctum')->get('/mto/{id}', [ProgramsController::class, 'show']);
Route::middleware('auth:sanctum')->put('/mto/{id}', [ProgramsController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/deleteMto/{id}', [ProgramsController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/inspections/{user}', [InspectionsController::class, 'index']);
Route::middleware('auth:sanctum')->get('/inspection/{id}', [InspectionsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/createInspection', [InspectionsController::class, 'create']);
Route::middleware('auth:sanctum')->post('/finishInspection', [EarringsController::class, 'create']);

Route::middleware('auth:sanctum')->get('/earrings', [EarringsController::class, 'index']);
Route::middleware('auth:sanctum')->get('/earring/{id}', [EarringsController::class, 'show']);
Route::middleware('auth:sanctum')->put('/earring/{id}', [EarringsController::class, 'update']);

Route::middleware('auth:sanctum')->post('/addCECO', [CustomersController::class, 'addceco']);
Route::middleware('auth:sanctum')->get('/cecosR', [CustomersController::class, 'cecosR']);
Route::middleware('auth:sanctum')->post('/createCustomer', [CustomersController::class, 'create']);
Route::middleware('auth:sanctum')->get('/customers', [CustomersController::class, 'index']);
Route::middleware('auth:sanctum')->delete('/cecos/{id}', [CustomersController::class, 'deleteCECOs']);
Route::middleware('auth:sanctum')->get('/cecos/{id}', [CustomersController::class, 'cecos']);
Route::middleware('auth:sanctum')->get('/customer/{id}', [CustomersController::class, 'edit']);
Route::middleware('auth:sanctum')->put('/customer/{id}', [CustomersController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/customers/{id}', [CustomersController::class, 'destroy']);

Route::middleware('auth:sanctum')->post('/createAddress', [PointsInterest::class, 'create']);
Route::middleware('auth:sanctum')->get('/addresses', [PointsInterest::class, 'index']);
Route::middleware('auth:sanctum')->post('/createRuta', [PointsInterest::class, 'createRuta']);
Route::middleware('auth:sanctum')->get('/rutas', [PointsInterest::class, 'rutas']);
Route::middleware('auth:sanctum')->get('/ruta/{id}', [PointsInterest::class, 'ruta']);
Route::middleware('auth:sanctum')->post('/editRuta', [PointsInterest::class, 'updateRuta']);
Route::middleware('auth:sanctum')->delete('/ruta/{id}', [PointsInterest::class, 'destroyRuta']);
Route::middleware('auth:sanctum')->get('/address/{id}', [PointsInterest::class, 'show']);
Route::middleware('auth:sanctum')->put('/address/{id}', [PointsInterest::class, 'update']);
Route::middleware('auth:sanctum')->delete('/address/{id}', [PointsInterest::class, 'destroy']);

Route::middleware('auth:sanctum')->post('/createPeaje', [PeajesController::class, 'create']);
Route::middleware('auth:sanctum')->get('/peajes', [PeajesController::class, 'index']);
Route::middleware('auth:sanctum')->get('/peaje/{id}', [PeajesController::class, 'show']);
Route::middleware('auth:sanctum')->put('/peaje/{id}', [PeajesController::class, 'update']);
Route::middleware('auth:sanctum')->post('/addCaseta', [PeajesController::class, 'addPeaje']);
Route::middleware('auth:sanctum')->get('/peajesR', [PeajesController::class, 'peajesR']);
Route::middleware('auth:sanctum')->get('/peajes/{id}', [PeajesController::class, 'peajes']);
Route::middleware('auth:sanctum')->delete('/peajeR/{id}/{ruta}', [PeajesController::class, 'destroyRuta']);
Route::middleware('auth:sanctum')->delete('/peaje/{id}', [PeajesController::class, 'destroy']);



