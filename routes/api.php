<?php

use App\Http\Controllers\AddressGroupController;
use App\Http\Controllers\ApplicantsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceSuppliersController;
use App\Http\Controllers\CategoryProductsController;
use App\Http\Controllers\CollaboratorsController;
use App\Http\Controllers\CtrlTiresController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DetailsRequisitionsController;
use App\Http\Controllers\DocsPDFsController;
use App\Http\Controllers\EarringsController;
use App\Http\Controllers\EntryDetailsController;
use App\Http\Controllers\ExpirationUnitsController;
use App\Http\Controllers\HistoryTireController;
use App\Http\Controllers\InspectionsController;
use App\Http\Controllers\InventoryDetailsController;
use App\Http\Controllers\InventoryEntriesController;
use App\Http\Controllers\InventoryOutputController;
use App\Http\Controllers\MayorAccountController;
use App\Http\Controllers\MissingDocsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OutputDetailsController;
use App\Http\Controllers\ParentAccountController;
use App\Http\Controllers\PaymentOrderController;
use App\Http\Controllers\PaymentSuppliersController;
use App\Http\Controllers\PeajesController;
use App\Http\Controllers\PointsInterest;
use App\Http\Controllers\ProductsServicesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\ProgramsMttoVehiclesController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RenovationsTireController;
use App\Http\Controllers\RepairTireController;
use App\Http\Controllers\RequisitionsController;
use App\Http\Controllers\RevisionsController;
use App\Http\Controllers\RevisionsTireController;
use App\Http\Controllers\SubTitleAccountController;
use App\Http\Controllers\SupplierBanckController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TestFlashSecurityController;
use App\Http\Controllers\TiresController;
use App\Http\Controllers\TitleAccountController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehousesController;
use App\Http\Controllers\WorkAreasController;
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

Route::middleware('auth:sanctum')->apiResource('applicants', ApplicantsController::class);
Route::middleware('auth:sanctum')->apiResource('trips', TripController::class);
Route::middleware('auth:sanctum')->get('/operators', [TripController::class, 'operatorsAll']);
Route::middleware('auth:sanctum')->get('/search', [TripController::class, 'search']);
Route::middleware('auth:sanctum')->post('/addUnit', [TripController::class, 'addUnit']);
Route::middleware('auth:sanctum')->delete('/deleteUnit/{id}', [TripController::class, 'deleteUnit']);
Route::middleware('auth:sanctum')->get('/finishTrip/{trip}', [TripController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/trip/{id}', [TripController::class, 'showTrip']);
Route::middleware('auth:sanctum')->get('/rutaTrip', [TripController::class, 'rutaTrip']);
Route::middleware('auth:sanctum')->get('/generar-pdf/{trip}', [TripController::class, 'generarPDF']);

Route::middleware('auth:sanctum')->get('/tripsCount', function () {
    $trips = DB::table('trips')->whereIn('status', [0, 1])->get();
    return response()->json(['total' => count($trips)]);
});

Route::middleware('auth:sanctum')
    ->get('/revisionCountByLogistic', [RevisionsController::class, 'countByLogistic']);
Route::middleware('auth:sanctum')
    ->get('/inspectionCountByLogistic', [InspectionsController::class, 'countByLogistic']);

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

Route::middleware('auth:sanctum')->apiResource('program-mttos', ProgramsController::class);
Route::middleware('auth:sanctum')->get('/unitsMto/{type}', [ProgramsController::class, 'units']);

Route::middleware('auth:sanctum')->apiResource('revisions', RevisionsController::class);
Route::middleware('auth:sanctum')->post('/finishRevision', [RevisionsController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/revisions-report/{id}', [RevisionsController::class, 'revisionsReport']);
Route::middleware('auth:sanctum')->get('/revisions-details/{id}', [RevisionsController::class, 'showDetails']);


Route::middleware('auth:sanctum')->apiResource('inspections', InspectionsController::class);
Route::middleware('auth:sanctum')->post('/finishInspection', [InspectionsController::class, 'finish']);
Route::middleware('auth:sanctum')->get('/inspections-report/{id}', [InspectionsController::class, 'inspectionsReport']);

Route::middleware('auth:sanctum')->apiResource('earrings', EarringsController::class);

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

Route::middleware('auth:sanctum')->apiResource('groups', AddressGroupController::class);

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
Route::middleware('auth:sanctum')->post('/pdf-mtto', [ProgramsMttoVehiclesController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->apiResource('tires', TiresController::class);
Route::middleware('auth:sanctum')->apiResource('ctrl-tires', CtrlTiresController::class);
Route::middleware('auth:sanctum')->apiResource('history-tires', HistoryTireController::class);
Route::middleware('auth:sanctum')->apiResource('revisions-tires', RevisionsTireController::class);
Route::middleware('auth:sanctum')->apiResource('renovation-tires', RenovationsTireController::class);
Route::middleware('auth:sanctum')->apiResource('repair-tires', RepairTireController::class);
//MODULES (CUENTAS, PORDUCTOS, CATEGORIAS, PROVVEDORES, AREAS)
Route::middleware('auth:sanctum')->apiResource('work-areas', WorkAreasController::class);
Route::middleware('auth:sanctum')->apiResource('collaborators', CollaboratorsController::class);
Route::middleware('auth:sanctum')->apiResource('parents-account', ParentAccountController::class);
Route::middleware('auth:sanctum')->apiResource('title-account', TitleAccountController::class);
Route::middleware('auth:sanctum')->apiResource('subtitle-account', SubTitleAccountController::class);
Route::middleware('auth:sanctum')->apiResource('mayor-account', MayorAccountController::class);
Route::middleware('auth:sanctum')->apiResource('categories', CategoryProductsController::class);
Route::middleware('auth:sanctum')->apiResource('products-services', ProductsServicesController::class);
Route::middleware('auth:sanctum')->get('/products-services-search', [ProductsServicesController::class, 'searchQuery']);
Route::middleware('auth:sanctum')->apiResource('suppliers', SuppliersController::class);
Route::middleware('auth:sanctum')->apiResource('suppliers-banck', SupplierBanckController::class);
//MODULE REQUISITIONS
Route::middleware('auth:sanctum')->apiResource('requisitions', RequisitionsController::class);
Route::middleware('auth:sanctum')->apiResource('details-requisitions', DetailsRequisitionsController::class);
Route::middleware('auth:sanctum')->get('/requisitions-pdf/{requisition}', [RequisitionsController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->put('/requisitions/{id}/status', [RequisitionsController::class, 'updateStatus']);
//MODULE ORDER COMPRA
Route::middleware('auth:sanctum')->get('/suppliers-search', [SuppliersController::class, 'searchQuery']);
Route::middleware('auth:sanctum')->apiResource('purchase-orders', PurchaseOrderController::class);
Route::middleware('auth:sanctum')->get('/purchaseOrders-pdf/{purchaseOrder}', [PurchaseOrderController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->put('/purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus']);
Route::middleware('auth:sanctum')->post('/update-pdf', [PurchaseOrderController::class, 'updatePdf']);

Route::middleware('auth:sanctum')->apiResource('test-flash', TestFlashSecurityController::class);

//MODULE PAYMENT ORDERS
Route::middleware('auth:sanctum')->apiResource('payment-orders', PaymentOrderController::class);
Route::middleware('auth:sanctum')->get('/payment-pdf/{order}', [PaymentOrderController::class, 'generarPDF']);
Route::middleware('auth:sanctum')->put('/payment-orders/{id}/status', [PaymentOrderController::class, 'updateStatus']);
Route::middleware('auth:sanctum')->post('/update-pdf-payment', [PaymentOrderController::class, 'updatePdf']);
Route::middleware('auth:sanctum')->apiResource('balance-suppliers', BalanceSuppliersController::class);
Route::middleware('auth:sanctum')->apiResource('payment-billings', PaymentSuppliersController::class);
//MODULE INVENTORIES
Route::middleware('auth:sanctum')->apiResource('warehouses', WarehousesController::class);
Route::middleware('auth:sanctum')->apiResource('inventory_details', InventoryDetailsController::class);
Route::middleware('auth:sanctum')->apiResource('inventory_entries', InventoryEntriesController::class);
Route::middleware('auth:sanctum')->apiResource('entry_details', EntryDetailsController::class);
Route::middleware('auth:sanctum')->get('/invoices/{supplierId}/status/{status}', [PaymentOrderController::class, 'getInvoicesWithAllStatusOrders']);
Route::middleware('auth:sanctum')->post('/pdf-balance', [BalanceSuppliersController::class, 'balancePDF']);
Route::middleware('auth:sanctum')->apiResource('inventory_outputs', InventoryOutputController::class);
Route::middleware('auth:sanctum')->apiResource('output_details', OutputDetailsController::class);
Route::middleware('auth:sanctum')->get('/output-pdf/{order}', [InventoryOutputController::class, 'generarPDF']);