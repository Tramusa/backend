<?php

use App\Http\Controllers\AddressGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CtrlTiresController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DocsPDFsController;
use App\Http\Controllers\EarringsController;
use App\Http\Controllers\ExpirationUnitsController;
use App\Http\Controllers\HistoryTireController;
use App\Http\Controllers\InspectionsController;
use App\Http\Controllers\MissingDocsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PeajesController;
use App\Http\Controllers\PointsInterest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\ProgramsMttoVehiclesController;
use App\Http\Controllers\RenovationsTireController;
use App\Http\Controllers\RevisionsController;
use App\Http\Controllers\RevisionsTireController;
use App\Http\Controllers\TiresController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\DB;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users', [AuthController::class, 'usersAll']);
    Route::put('/updateStatus', [AuthController::class, 'updateStatus']);
    Route::post('/upData', [AuthController::class, 'proceedingsUp']);
    Route::get('/user/{id}', [AuthController::class, 'show']);
    Route::get('/userProceedings/{id}', [AuthController::class, 'proceedings']);
    Route::post('/userAdmin', [AuthController::class, 'updateAdmin']);
    Route::delete('/expirationsUnits/{id}', [ExpirationUnitsController::class, 'destroy']);
    Route::put('/orders/{id}', [OrderController::class, 'cancel']);
    Route::middleware('auth:sanctum')->delete('/unit/{id}', [UnitController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->post('/change-password', [ProfileController::class, 'changePassword']);
Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'show']);
Route::middleware('auth:sanctum')->post('/user', [AuthController::class, 'update']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/createUnit', [UnitController::class, 'create']);
Route::middleware('auth:sanctum')->post('/unit', [UnitController::class, 'unit']);
Route::middleware('auth:sanctum')->get('/units/{type}', [UnitController::class, 'units']);
Route::middleware('auth:sanctum')->post('/unit/{type}', [UnitController::class, 'update']);
Route::middleware('auth:sanctum')->post('/upload-pdf', [UnitController::class, 'upload']);
Route::middleware('auth:sanctum')->post('/docsUnits', [UnitController::class, 'show']);
Route::middleware('auth:sanctum')->delete('/docsUnits/{id}', [UnitController::class, 'destroyDocs']);

Route::middleware('auth:sanctum')->post('/createTrip', [TripController::class, 'create']);
Route::middleware('auth:sanctum')->get('/operators', [TripController::class, 'operatorsAll']);
Route::middleware('auth:sanctum')->get('/search', [TripController::class, 'search']);
Route::middleware('auth:sanctum')->post('/addUnit', [TripController::class, 'addUnit']);
Route::middleware('auth:sanctum')->get('/unitsTrip/{trip}', [TripController::class, 'show']);
Route::middleware('auth:sanctum')->delete('/deleteUnit/{id}', [TripController::class, 'deleteUnit']);
Route::middleware('auth:sanctum')->put('/updateTrip/{trip}', [TripController::class, 'update']);
Route::middleware('auth:sanctum')->get('/trips/{type}', [TripController::class, 'showTrips']);
Route::middleware('auth:sanctum')->get('/finishTrip/{trip}', [TripController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/trip/{id}', [TripController::class, 'showTrip']);
Route::middleware('auth:sanctum')->get('/rutaTrip', [TripController::class, 'rutaTrip']);
Route::middleware('auth:sanctum')->get('/generar-pdf/{trip}', [TripController::class, 'generarPDF']);

Route::middleware('auth:sanctum')->get('/tripsCount', function () {
    $trips = DB::table('trips')->whereIn('status', [0, 1])->get();
    return response()->json(['total' => count($trips)]);
});
Route::middleware('auth:sanctum')->get('/inspectionCount/{user}', function ($user) {
    $inspections = DB::table('inspections')->where('status', 1)->where('responsible', $user)->get();
    return response()->json(['total' => count($inspections)]);
});
Route::middleware('auth:sanctum')->get('/inspectionsCount', function () {
    $inspections = DB::table('inspections')->where('status', 1)->get();
    return response()->json(['total' => count($inspections)]);
});
Route::middleware('auth:sanctum')->get('/revisionCount/{user}', function ($user) {
    $revisions = DB::table('revisions')->where('status', 1)->where('responsible', $user)->get();
    return response()->json(['total' => count($revisions)]);
});
Route::middleware('auth:sanctum')->get('/revisionsCount', function () {
    $revisions = DB::table('revisions')->where('status', 1)->get();
    return response()->json(['total' => count($revisions)]);
});
Route::middleware('auth:sanctum')->get('/earringsCount', function () {
    $earrings = DB::table('earrings')->where('status', 1)->get();
    return response()->json(['total' => count($earrings)]);
});
Route::middleware('auth:sanctum')->get('/missingCount', function () {
    $missing = DB::table('missing_docs')->where('status', 1)->get();
    return response()->json(['total' => count($missing)]);
});
Route::middleware('auth:sanctum')->get('/expirationCount', function () {
    $expiration = DB::table('expiration_units')->where('status', 1)->get();
    return response()->json(['total' => count($expiration)]);
});
Route::middleware('auth:sanctum')->get('/ordersCount', function () {
    $orders = DB::table('orders')->whereIn('status', [1, 2, 3])->get();
    return response()->json(['total' => count($orders)]);
});

Route::middleware('auth:sanctum')->get('/responsible', function () {
    $users = DB::table('users')
        ->where('active', 1)
        ->where('rol', '!=', '')
        ->get();
    return response()->json($users);
});

Route::middleware('auth:sanctum')->get('/programs', [ProgramsController::class, 'index']);
Route::middleware('auth:sanctum')->post('/addMto', [ProgramsController::class, 'create']);
Route::middleware('auth:sanctum')->get('/unitsMto/{type}', [ProgramsController::class, 'units']);
Route::middleware('auth:sanctum')->get('/mto/{id}', [ProgramsController::class, 'show']);
Route::middleware('auth:sanctum')->put('/mto/{id}', [ProgramsController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/deleteMto/{id}', [ProgramsController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/revisions', [RevisionsController::class, 'index']);
Route::middleware('auth:sanctum')->get('/revision/{id}', [RevisionsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/createRevision', [RevisionsController::class, 'create']);
Route::middleware('auth:sanctum')->post('/finishRevision', [RevisionsController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/revisions-report/{id}', [RevisionsController::class, 'revisionsReport']);
Route::middleware('auth:sanctum')->put('/revision/{id}', [RevisionsController::class, 'update']);
Route::middleware('auth:sanctum')->get('/revisions-details/{id}', [RevisionsController::class, 'showDetails']);

Route::middleware('auth:sanctum')->get('/inspections', [InspectionsController::class, 'index']);
Route::middleware('auth:sanctum')->get('/inspection/{id}', [InspectionsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/createInspection', [InspectionsController::class, 'create']);
Route::middleware('auth:sanctum')->post('/finishInspection', [InspectionsController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/inspections-report/{id}', [InspectionsController::class, 'inspectionsReport']);

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
Route::middleware('auth:sanctum')->get('/generar-pdf', [PeajesController::class, 'generarPDF']);

Route::middleware('auth:sanctum')->get('/groups', [AddressGroupController::class, 'index']);
Route::middleware('auth:sanctum')->post('/createGroup', [AddressGroupController::class, 'create']);
Route::middleware('auth:sanctum')->get('/group/{id}', [AddressGroupController::class, 'show']);
Route::middleware('auth:sanctum')->put('/group/{id}', [AddressGroupController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/group/{id}', [AddressGroupController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/expirationsUnits', [ExpirationUnitsController::class, 'index']);
Route::middleware('auth:sanctum')->get('/expirationUnit/{id}', [ExpirationUnitsController::class, 'show']);
Route::middleware('auth:sanctum')->post('/updatePDF', [ExpirationUnitsController::class, 'updatePDF']);
Route::middleware('auth:sanctum')->post('/updateDate', [ExpirationUnitsController::class, 'updateDate']);
Route::middleware('auth:sanctum')->put('/updateExpirationStatus/{id}', [ExpirationUnitsController::class, 'update']);
Route::middleware('auth:sanctum')->post('/createOrder', [OrderController::class, 'store']);
Route::middleware('auth:sanctum')->get('/orders/{type}', [OrderController::class, 'show']);
Route::middleware('auth:sanctum')->get('/orders-bitacora', [OrderController::class, 'bitacora']);
Route::middleware('auth:sanctum')->get('/order/{id}', [OrderController::class, 'showOrder']);
Route::middleware('auth:sanctum')->get('/orderEarrings/{id}', [OrderController::class, 'orderEarrings']);
Route::middleware('auth:sanctum')->delete('/orderEarrings/{id}', [OrderController::class, 'delete']);
Route::middleware('auth:sanctum')->put('/order/{id}', [OrderController::class, 'update']);
Route::middleware('auth:sanctum')->get('/order-pdf/{order}', [OrderController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->post('/generate-pdf-filter', [OrderController::class, 'generatePDFfilter']);
Route::middleware('auth:sanctum')->post('/upload-excel', [TripController::class, 'import']);
Route::middleware('auth:sanctum')->get('/download-excel', [TripController::class, 'download']);  

Route::middleware('auth:sanctum')->apiResource('docs-pdfs', DocsPDFsController::class);
Route::middleware('auth:sanctum')->post('/docs-pdfs/{id}', [DocsPDFsController::class, 'update']);

Route::middleware('auth:sanctum')->apiResource('missing-docs', MissingDocsController::class);
Route::middleware('auth:sanctum')->apiResource('programs-mtto', ProgramsMttoVehiclesController::class);
Route::middleware('auth:sanctum')->get('/pdf-mtto/{activity}', [ProgramsMttoVehiclesController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->apiResource('tires', TiresController::class);
Route::middleware('auth:sanctum')->apiResource('ctrl-tires', CtrlTiresController::class);
Route::middleware('auth:sanctum')->apiResource('history-tires', HistoryTireController::class);
Route::middleware('auth:sanctum')->apiResource('revisions-tires', RevisionsTireController::class);
Route::middleware('auth:sanctum')->apiResource('renovation-tires', RenovationsTireController::class);