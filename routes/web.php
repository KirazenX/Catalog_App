<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('catalog', function () {
        return view('pages.catalog.index');
    })->name('catalog');

    Route::get('products/{product}', function (Product $product) {
        return view('pages.catalog.show', compact('product'));
    })->name('products.show');

    Route::get('admin/products', function () {
        return view('pages.admin.products');
    })->name('admin.products');
});

require __DIR__.'/settings.php';