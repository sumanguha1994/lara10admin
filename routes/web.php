<?php
use Illuminate\Support\Facades\Route;

//Site Routes
Route::middleware('revalidate')
    ->group(__DIR__ . '/site/site.php');

//Admin Routes
Route::namespace ('Admin')->middleware('revalidate')
    ->name('admin.')->prefix('admin')
    ->group(__DIR__ . '/admin/admin.php');
