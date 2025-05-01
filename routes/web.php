<?php

use App\Http\Controllers\Download;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin/download'], function () {
    Route::get('/item-entries', [Download::class, 'ItemEntry'])->name('admin.download.item-entries');
    Route::get('/item-drawals', [Download::class, 'ItemWithdrawal'])->name('admin.download.item-drawals');
});
