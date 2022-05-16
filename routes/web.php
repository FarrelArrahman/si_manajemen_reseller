<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductVariantStockLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/test', function() {
    $product = App\Models\ProductVariant::where('product_id', 1)->withTrashed()->get();
    return response()->json(['data' => $product]);
});

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::get('/', function() {
        return redirect()->route('dashboard');        
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // APIs
    Route::prefix('/api/v1')->group(function() {
        // Category API
        Route::get('/category', [CategoryController::class, 'index_dt'])->name('category.index_dt');

        // Product API
        Route::get('/product', [ProductController::class, 'index_dt'])->name('product.index_dt');

        // Inventory API
        Route::get('/inventory', [InventoryController::class, 'index_dt'])->name('inventory.index_dt');

        // Product Variant API
        Route::get('/product/{product}/variant', [ProductVariantController::class, 'index_dt'])->name('product_variant.index_dt');
        Route::get('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'checkVariant'])->name('product_variant.checkVariant');
        Route::get('/variant/{productVariant}/color', [ProductVariantController::class, 'color'])->name('product_variant.color');

        // Product Variant Stock Log API
        Route::get('/variant/{productVariant}/log', [ProductVariantStockLogController::class, 'index_dt'])->name('product_variant_stock_log.index_dt');
    });
    
    // Product
    // Route::get('/product/{product}/detail', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product/{product}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::patch('/product/{product}/change-status', [ProductController::class, 'changeStatus'])->name('product.changeStatus');
    Route::resource('product', ProductController::class);

    // Product Variant
    Route::get('/product/{product}/variant/', [ProductVariantController::class, 'create'])->name('product_variant.create');
    Route::post('/product/{product}/variant/', [ProductVariantController::class, 'store'])->name('product_variant.store');
    Route::get('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'show'])->name('product_variant.show');
    Route::get('/product/{product}/variant/{productVariant}/edit', [ProductVariantController::class, 'edit'])->name('product_variant.edit');
    Route::put('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'update'])->name('product_variant.update');
    Route::delete('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'destroy'])->name('product_variant.destroy');
    Route::get('/product/{product}/variant/{productVariant}/restore', [ProductVariantController::class, 'restore'])->name('product_variant.restore');
    Route::patch('/product/{product}/variant/{productVariant}/change-status', [ProductVariantController::class, 'changeStatus'])->name('product_variant.changeStatus');
    Route::patch('/product/{product}/variant/{productVariant}/change-stock', [ProductVariantController::class, 'changeStock'])->name('product_variant.changeStock');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Category
    Route::patch('/category/{category}/change-status', [CategoryController::class, 'changeStatus'])->name('category.changeStatus');
    Route::resource('category', CategoryController::class);
});
