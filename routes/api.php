<?php

use App\Http\API\APIController;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('v1')->group(function () {
    Route::post('upsert/{tablename}/{sifre}', [APIController::class, 'Insert']);
    Route::post('product/all', [APIController::class, 'GetAllProducts']);
    Route::post('getlocalelang', [APIController::class, 'GetLocaleLang']);
    Route::post('product/subcategory/{id}', [APIController::class, 'GetSubCategories']);
    Route::post('product/category/{id}', [APIController::class, 'GetProductCategories']);
    Route::post('save/image/{sifre}', [APIController::class, 'SaveImageFileToServer']);
    Route::post('translate/add/{sifre}', [APIController::class, 'AddTranslateToLanguageFile']);
    Route::post('getforms', [MainController::class, 'GetAllForms']);

    Route::post('call/waiter/{qrcode}', [APIController::class, 'AddWaiterCallToTable']);

    // Masaüstü (ve İleride Mobil) Senkronizasyon Uçları
    Route::post('desktop/sync/tables', [\App\Http\Controllers\Api\DesktopSyncController::class, 'syncTables']);
    Route::post('desktop/sync/kasa', [\App\Http\Controllers\Api\DesktopSyncController::class, 'syncKasa']);
    Route::get('desktop/status', [\App\Http\Controllers\Api\DesktopSyncController::class, 'getStatus']);
});
