<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\PaymentController;
use App\Models\Category;
use Kavist\RajaOngkir\RajaOngkir;
    

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    $categories = Category::all();
    $products = Product::all()->take(15);

    return view('dashboard', compact('categories', 'products'));
})->name('dashboard');
// ->middleware(['auth', 'verified'])

Route::get('/product', function () {
    return view('product.show');
});

Route::get('/product/productcategory', function () {
    return view('product.productcategory');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{id}', [CartController::class, 'create'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/update-quantity/{id}', [CartController::class, 'update_quantity'])->name('cart.update-quantity');

    Route::get('/cart/payment', [PaymentController::class, 'index'])->name('cart.checkout');
    Route::get('/historyorder', [PaymentController::class, 'processpayment'])->name('history.order');
    Route::get('/summaryorder', [PaymentController::class, 'summaryorder'])->name('summary.order');

    Route::get('/add-address', [AddressController::class, 'index'])->name('add.new-address');
    Route::post('/add-address', [AddressController::class, 'store'])->name('address.store');

    Route::get('/api/provinces', function (RajaOngkir $rajaOngkir) {
        $daftarProvinsi = $rajaOngkir->provinsi()->all();
        return response()->json($daftarProvinsi);
    });

    Route::get('/api/cities-by-province/{province_id}', function (RajaOngkir $rajaOngkir, $province_id) {
        $daftarKota = $rajaOngkir->kota()->dariProvinsi($province_id)->get();

        return response()->json($daftarKota);
    });

    Route::get('/api/ongkos-kirim/{origin}/{destination}/{weight}/{courier}', function (RajaOngkir $rajaOngkir, $origin, $destination, $weight, $courier) {
        $cost = $rajaOngkir->ongkosKirim([
            'origin'        => $origin,     
            'destination'   => $destination,      
            'weight'        => $weight,    
            'courier'       => $courier   
        ])->get();

        $nama_jasa = $cost[0]['name'];

        foreach ($cost[0]['costs'] as $row)
        {
            $daftarService[] = array(
                'description' => $row['description'],
                'biaya' => $row['cost'][0]['value'],
                'etd' => $row['cost'][0]['etd']
            );
        }

        return response()->json([
            'nama_jasa' => $nama_jasa,
            'services' => $daftarService
        ]);
    });

    Route::get('/history-order', [OrderController::class, 'index'])->name('history.order');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


    Route::get('/seller/setup', [SellerController::class, 'setup'])->name('seller.register');
    Route::post('/seller', [SellerController::class, 'store'])->name('seller.store');
    Route::put('/seller', [SellerController::class, 'update_profile'])->name('seller.profile.update');

    Route::post('/products/{product}/ratings', [ProductRatingController::class, 'store'])
    ->name('products.ratings.store');


    Route::middleware('seller')->group(function () {
        Route::get('/seller/index', [SellerController::class, 'index'])->name('seller.profile');
        Route::get('/seller/product', [SellerController::class, 'product'])->name('seller.product');
        Route::post('/seller/create', [SellerController::class, 'create_product'])->name('seller.create-product');
        Route::get('/seller/editproduct/{id}', [SellerController::class, 'editproduct'])->name('seller.edit-product');
        Route::get('/seller/products/{id}/edit', [SellerController::class, 'editProduct'])->name('seller.products.edit');
        Route::put('/seller/products/{id}', [SellerController::class, 'update'])->name('seller.products.update');
        Route::delete('/product/{id}', [SellerController::class, 'destroy'])->name('product.destroy');
    });
});

require __DIR__.'/auth.php';
