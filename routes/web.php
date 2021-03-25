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
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
	
	/*  Route::get('/plan/{id}/edit','App\Http\Controllers\PlanController@edit')->middleware('admin.user')->name('admin.plan.edit'); */
	Route::get('/plans/{id}/edit', ['uses' => 'App\Http\Controllers\PlanController@edit','as' => 'admin.plan.edit']);
	//Route::get('/plans/create/', ['uses' => 'App\Http\Controllers\PlanController@create','as' => 'admin.plan.create']);
	Route::patch('plans/update/{id}', array('as' => 'admin.plan.update', 'uses' => 'App\Http\Controllers\PlanController@update'));
	//Route::post('plans/store', array('as' => 'admin.plan.store', 'uses' => 'App\Http\Controllers\PlanController@store'));
	Route::get('plans/delete/{id}', array('as' => 'admin.plan.destroy', 'uses' => 'App\Http\Controllers\PlanController@destroy'));

	Route::post('ajax/request/status', array('as' => 'ajax.request.status', 'uses' => 'App\Http\Controllers\PlanController@change_status'));
	
	
	
	 //Routes for create Plan
  
	Route::get('/plans/create/', ['uses' => 'App\Http\Controllers\SubscriptionController@createPlan','as' => 'admin.plan.create']);
    Route::post('store/plan', 'App\Http\Controllers\SubscriptionController@storePlan')->name('admin.plan.store');
	
	
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', function(){
		return view('home');
	})->name('home');
	Route::get('/plans', array('as' => 'plans.index', 'uses' => 'App\Http\Controllers\PlanController@allplans'));
   
   Route::get('/plan/{plan}', array('as' => 'plans.show', 'uses' => 'App\Http\Controllers\PlanController@show'));
    Route::post('/subscription', 'App\Http\Controllers\SubscriptionController@create')->name('subscription.create');

});
