<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductVariantStockLogController;
use App\Http\Controllers\RajaongkirController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Carbon\Carbon;

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

// Route for testing and debugging
// Route::get('/test', function() {
//     return Carbon::createFromDate('2022', '07', 1)->endOfMonth();
// });

// Route::get('/test-email', function() {
//     $details = [
//         'email' => "testkirim@laudable-me.com",
//         'subject' => "Verifikasi Data Reseller Baru",
//         'message' => 'User **Heheboi** telah mengajukan data reseller untuk diverifikasi. Silakan kunjungi halaman **Reseller** pada menu **User > Reseller** atau klik tombol di bawah ini.',
//         'button' => 'Lihat Daftar Reseller',
//         'url' => route('user.index', 'reseller')
//     ];

//     dispatch(new App\Jobs\SendEmailJob($details));
//     dd("Email sent!");
// });

// Route::get('/test', function(Request $request) {
//     dd(App\Models\Order::find(1)->orderShipping);
// });

// Route::post('verified', function(Request $request) {
//     $message = [
//         'id' => 3,
//         'success' => true,
//         'message' => $request->message
//     ];

//     ResellerEvent::dispatch($message);
// });

// Route autentikasi
Auth::routes();

// Route khusus yang sudah login
Route::middleware(['auth'])->group(function() {
    // APIs
    Route::prefix('/api/v1')->group(function() {
        // Category API
        Route::get('/category', [CategoryController::class, 'index_api'])->name('category.index_api');
        Route::get('/category/datatable', [CategoryController::class, 'index_dt'])->name('category.index_dt');

        // Product API
        Route::get('/product', [ProductController::class, 'index_api'])->name('product.index_api');
        Route::get('/product/datatable', [ProductController::class, 'index_dt'])->name('product.index_dt');

        // Inventory API
        Route::get('/inventory/datatable', [InventoryController::class, 'index_dt'])->name('inventory.index_dt');
        Route::get('/inventory/{productVariant}/check-quantity', [InventoryController::class, 'checkQuantity'])->name('inventory.checkQuantity');

        // Product Variant API
        Route::get('/product/{product}/variant', [ProductVariantController::class, 'index_dt'])->name('product_variant.index_dt');
        Route::get('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'checkVariant'])->name('product_variant.checkVariant');
        Route::get('/variant/search', [ProductVariantController::class, 'search'])->name('product_variant.search');
        Route::get('/variant/{productVariant}', [ProductVariantController::class, 'detail'])->name('product_variant.detail');
        Route::get('/variant/{productVariant}/color', [ProductVariantController::class, 'color'])->name('product_variant.color');

        // Product Variant Stock Log API
        Route::get('/variant/{productVariant}/log', [ProductVariantStockLogController::class, 'index_dt'])->name('product_variant_stock_log.index_dt');

        // User API
        Route::get('/user/{role}', [UserController::class, 'index'])->name('user.index_api');
        Route::get('/user/{role}/datatable', [UserController::class, 'index_dt'])->name('user.index_dt');

        // Reseller API
        Route::get('/reseller/datatable', [ResellerController::class, 'index_dt'])->name('reseller.index_dt');
        Route::get('/reseller/pending', [ResellerController::class, 'pending'])->name('reseller.pending');
        Route::get('/reseller/{reseller}', [ResellerController::class, 'detail'])->name('reseller.detail');
        
        // Announcement API
        Route::get('/announcement/datatable', [AnnouncementController::class, 'index_dt'])->name('announcement.index_dt');

        // Rajaongkir API
        Route::get('/province', [RajaongkirController::class, 'provinceAPI'])->name('rajaongkir.province');
        Route::get('/city', [RajaongkirController::class, 'cityAPI'])->name('rajaongkir.city');
        Route::post('/cost', [RajaongkirController::class, 'costAPI'])->name('rajaongkir.cost');

        // Cart API
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::get('/cart/datatable', [CartController::class, 'index_dt'])->name('cart.index_dt');
        Route::get('/cart/show', [CartController::class, 'show'])->name('cart.show');
        Route::patch('/cart/{cartDetail}/change-quantity', [CartController::class, 'changeQuantity'])->name('cart.changeQuantity');
        Route::delete('/cart/{cartDetail}/delete', [CartController::class, 'removeCartItem'])->name('cart.removeCartItem');
        Route::delete('/cart/delete', [CartController::class, 'removeAll'])->name('cart.removeAll');

        // Order API
        Route::get('/order/datatable', [OrderController::class, 'index_dt'])->name('order.index_dt');
        Route::get('/order/pending', [OrderController::class, 'pending'])->name('order.pending');
        Route::get('/order/{order}', [OrderController::class, 'detail'])->name('order.detail');

        // Order Payment API
        Route::get('/order_payment/datatable', [OrderPaymentController::class, 'index_dt'])->name('order_payment.index_dt');
        Route::get('/order_payment/pending', [OrderPaymentController::class, 'pending'])->name('order_payment.pending');
        Route::get('/order_payment/{order_payment}', [OrderPaymentController::class, 'detail'])->name('order_payment.detail');

        // Report API (General)
        Route::post('/report/general/selling', [ReportController::class, 'generalSellingReport'])->name('report.generalSellingReport');
        
        // Report API (Selling Recap)
        Route::post('/report/selling-recap', [ReportController::class, 'sellingRecapReport'])->name('report.sellingRecapReport');
        
        // Report API (Product Selling)
        Route::post('/report/product-selling', [ReportController::class, 'productSellingReport'])->name('report.productSellingReport');

        //Pelanggan API
        Route::get('/pelanggan/datatable', [PelangganController::class, 'index_dt'])->name('pelanggan.index_dt');
    });
    
    // Base URL (Redirect to Dashboard)
    Route::get('/', function() {
        return redirect()->route('dashboard');        
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Group khusus Admin dan Staff
    Route::middleware('role:Admin,Staff')->group(function() {
        // Product
        Route::get('/product/{product}/restore', [ProductController::class, 'restore'])->name('product.restore');
        Route::patch('/product/{product}/change-status', [ProductController::class, 'changeStatus'])->name('product.changeStatus');
        Route::resource('product', ProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Product Variant
        Route::get('/product/{product}/variant/', [ProductVariantController::class, 'create'])->name('product_variant.create');
        Route::post('/product/{product}/variant/', [ProductVariantController::class, 'store'])->name('product_variant.store');
        Route::get('/product/{product}/variant/{productVariant}/edit', [ProductVariantController::class, 'edit'])->name('product_variant.edit');
        Route::put('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'update'])->name('product_variant.update');
        Route::delete('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'destroy'])->name('product_variant.destroy');
        Route::get('/product/{product}/variant/{productVariant}/restore', [ProductVariantController::class, 'restore'])->name('product_variant.restore');
        Route::patch('/product/{product}/variant/{productVariant}/change-status', [ProductVariantController::class, 'changeStatus'])->name('product_variant.changeStatus');
        Route::patch('/product/{product}/variant/{productVariant}/change-stock', [ProductVariantController::class, 'changeStock'])->name('product_variant.changeStock');

        // Category
        Route::patch('/category/{category}/change-status', [CategoryController::class, 'changeStatus'])->name('category.changeStatus');
        Route::resource('category', CategoryController::class);

        // User
        Route::get('/user/{role}', [UserController::class, 'index'])->name('user.index'); 
        Route::get('/user/{role}/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/{role}/', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{role}/{user}', [UserController::class, 'show'])->name('user.show');
        Route::delete('/user/{role}/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('/user/{role}/{user}/restore', [UserController::class, 'restore'])->name('user.restore');

        // Reseller
        Route::get('/reseller', [ResellerController::class, 'index'])->name('reseller.index');
        Route::patch('/reseller/{reseller}/verify', [ResellerController::class, 'verify'])->name('reseller.verify');
        Route::patch('/reseller/{reseller}/change-status', [ResellerController::class, 'changeStatus'])->name('reseller.changeStatus');

        // Announcement
        Route::patch('/announcement/{announcement}/change-status', [AnnouncementController::class, 'changeStatus'])->name('announcement.changeStatus');
        Route::resource('announcement', AnnouncementController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Order
        Route::patch('/order/{order}/verify', [OrderController::class, 'verify'])->name('order.verify');

        // Order Payment
        Route::patch('/order_payment/{order}/verify', [OrderPaymentController::class, 'verify'])->name('order_payment.verify');

        // Report
        Route::get('/report/general', [ReportController::class, 'general'])->name('report.general');
        Route::get('/report/selling-recap', [ReportController::class, 'sellingRecap'])->name('report.sellingRecap');
        Route::post('/report/selling-recap/excel', [ReportController::class, 'sellingRecapExcel'])->name('report.sellingRecapExcel');
        Route::get('/report/product-selling', [ReportController::class, 'productSelling'])->name('report.productSelling');
        Route::post('/report/product-selling/excel', [ReportController::class, 'productSellingExcel'])->name('report.productSellingExcel');

        // Configuration
        Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
        Route::put('/configuration', [ConfigurationController::class, 'update'])->name('configuration.update');
    });
    
    // Route Group untuk Admin, Staff dan Reseller (hanya yang sudah diverifikasi)
    Route::middleware('reseller.active')->group(function() {
        // Product
        Route::resource('product', ProductController::class)->only(['show']);
        
        // Product Variant
        Route::get('/product/{product}/variant/{productVariant}', [ProductVariantController::class, 'show'])->name('product_variant.show');

        // Order
        Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
        Route::post('/order', [OrderController::class, 'store'])->name('order.store');
        Route::delete('/order/{order}/cancel', [OrderController::class, 'destroy'])->name('order.destroy');
        Route::get('/order/{code}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');
    });

    // Product
    Route::resource('product', ProductController::class)->only(['index']);

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // User
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/{role}/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{role}/{user}', [UserController::class, 'update'])->name('user.update');

    // Reseller
    Route::get('/reseller/edit', [ResellerController::class, 'edit'])->name('reseller.edit');
    Route::put('/reseller/update', [ResellerController::class, 'update'])->name('reseller.update');

    // Announcement
    Route::resource('announcement', AnnouncementController::class)->only(['index','show']);

    // Order
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    
    // Order Payment
    Route::get('/order_payment', [OrderPaymentController::class, 'index'])->name('order_payment.index');
    Route::post('/order_payment/{order}/upload', [OrderPaymentController::class, 'upload'])->name('order_payment.upload');

    // FAQ
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
   
    // Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::delete('/pelanggan/{pelanggan}/destroy', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
});
