<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/cart',function (){
   return view('table_default');
});
/********** Rute za stolove i poručivanje hrane ********/
Route::get('/table',function(){
    return view('table_default');
});
Route::get('/table/categories',function(){
    return view('table_default');
});
Route::get('/table/categories/{category}',function($category){
    return view('table_default',['category'=>$category]);
});
Route::get('/table/categories/{category}/{subCategory}',function($category,$subCategory){
    return view('table_default',['category'=>$category,'subCategory'=>$subCategory]);
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/********* KREIRANJE NOVIH RUTA SLUGS-A ********/
//Dodati middleware
Route::group(['middleware' => [
    'auth:sanctum',
    'verified'
    ,'accessrole']], function () {

    Route::get('/dashboard',function (){
        return view('dashboard');
    })->name('dashboard');

    Route::get('/tables',function (){
        return view('admin.tables');
    })->name('tables');

    Route::get('/users',function (){
        return view('admin.users');
    })->name('users');

    Route::get('/categories',function (){
        return view('admin.categories');
    })->name('categories');

    Route::get('/sub-categories',function (){
        return view('admin.sub-categories');
    })->name('sub-categories');

    Route::get('/articles',function (){
        return view('admin.articles');
    })->name('articles');

    Route::get('/view-orders',function (){
        return view('admin.view-orders');
    })->name('view-orders');

});
