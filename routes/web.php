<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\RosaryCategoryController;
use App\Http\Controllers\Admin\RosaryPrayerAdminController;
use App\Http\Controllers\Admin\RosarySubCategoryController;

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
// Route::get('/',[App\Http\Controllers\BlogController::class,'blogview']);

Route::get('/', [App\Http\Controllers\AdminController::class, 'login_check'])->name('admin.login_check');

Route::get('/api',[App\Http\Controllers\Api\ApiController::class,'check']);


Route::get('auth/login', function () {
    return view('auth.login');
});

Route::get('/page-not-found', function () {
    return view('admin.prayer.error');
});


// Route::get('add-to-log', 'App\Http\Controllers\HomeController@myTestAddToLog');

Route::group(['prefix' => 'admin'], function()
{
	Route::group(['middleware' => 'admin.guest'], function()
    {
		Route::view('/login','admin.login')->name('admin.login');
		Route::post('/login',[App\Http\Controllers\AdminController::class, 'authenticate'])->name('admin.auth');
	});

	Route::group(['middleware' => 'admin.auth'], function()
    {
        Route::any('/dashboard', [App\Http\Controllers\AdminController::class,'dashboard'])->name('admin/dashboard');

        //Accounts list

        Route::any('/superadmin',[App\Http\Controllers\AdminController::class,'superadmin'])->name('superadmin');

        Route::get('/admin_add_sub_admin',[App\Http\Controllers\AdminController::class,'add']);

        Route::post('/admin_insert',[App\Http\Controllers\AdminController::class,'insert']);

        Route::any('/edit_admin/{id}',[App\Http\Controllers\AdminController::class,'edit']);

        Route::any('/edit_admin_list/{id}',[App\Http\Controllers\AdminController::class,'editlist']);

        Route::post('/user_status_update', [App\Http\Controllers\AdminController::class, 'active']);

        Route::post('/delete_admin/{id}',[App\Http\Controllers\AdminController::class,'delete']);

        Route::get('/logActivity', 'App\Http\Controllers\HomeController@logActivity');

        Route::post('/activitydelete',[App\Http\Controllers\AdminController::class,'activitydelete']);

        route::post('/manage/{id}',[App\Http\Controllers\AdminController::class,'manage']);


        //profile update

        Route::get('/profile_update', function () {return view('admin.accounts.profile_update');});

        Route::any('/home','App\Http\Controllers\AdminController@profileUpdate')->name('profileupdate');

        Route::post('/uploadFile', 'App\Http\Controllers\CategoryController@uploadFile');



	});

    Route::get('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
});

Route::get('/webform', function () {
    return view('webform');
});

Route::get('{any}', function () {
    return view('admin.error');
});

Route::prefix('admin')->group(function () {

    // Rosary Categories
    Route::get('rosary-categories', [RosaryCategoryController::class, 'index']);
    Route::get('rosary-categories/create', [RosaryCategoryController::class, 'create']);
    Route::post('rosary-categories', [RosaryCategoryController::class, 'store']);
    Route::get('rosary-categories/{id}/edit', [RosaryCategoryController::class, 'edit']);
    Route::post('rosary-categories/{id}/update', [RosaryCategoryController::class, 'update']);
    Route::get('rosary-categories/{id}/delete', [RosaryCategoryController::class, 'destroy']);

    // Rosary Prayers
    Route::get('rosary-prayers', [RosaryPrayerAdminController::class, 'index']);
    Route::get('rosary-prayers/create', [RosaryPrayerAdminController::class, 'create']);
    Route::post('rosary-prayers', [RosaryPrayerAdminController::class, 'store']);
    Route::get('rosary-prayers/{id}/edit', [RosaryPrayerAdminController::class, 'edit']);
    Route::post('rosary-prayers/{id}/update', [RosaryPrayerAdminController::class, 'update']);
    Route::get('rosary-prayers/{id}/delete', [RosaryPrayerAdminController::class, 'destroy']);

    Route::get('rosary-sub-categories', [RosarySubCategoryController::class, 'index']);
    Route::get('rosary-sub-categories/create', [RosarySubCategoryController::class, 'create']);
    Route::post('rosary-sub-categories', [RosarySubCategoryController::class, 'store']);
    Route::get('rosary-sub-categories/{id}/edit', [RosarySubCategoryController::class, 'edit']);
    Route::post('rosary-sub-categories/{id}/update', [RosarySubCategoryController::class, 'update']);
    Route::get('rosary-sub-categories/{id}/delete', [RosarySubCategoryController::class, 'destroy']);

    // API test page
    Route::get('rosary-api-list', function () {
        return view('admin.rosary.api_list');
    });
});