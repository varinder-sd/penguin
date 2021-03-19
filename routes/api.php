<?php
use Illuminate\Support\Facades\App;
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


Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/signup', 'App\Http\Controllers\AuthController@register'); // Signup

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/user', function (Request $request) {
		return $request->user();
	});
	Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
	
	
	Route::get('/plans', function (Request $request) {
       // return $request->user();
		$plans = \App\Models\Plan::all();
		return response()->json([
		  'status_code' => 200,
		  'plans' => $plans,
		]);
    });
	
	Route::post('/subscription', 'App\Http\Controllers\SubscriptionController@create')->name('subscription.create');
	Route::get('/user-subscription', 'App\Http\Controllers\SubscriptionController@userSubscription')->name('subscription.user');
});

Route::get('/planAll', 'App\Http\Controllers\SubscriptionController@retrievePlans')->name('subscription.plans');

Route::get('countries', function() {
	
	$countries = \App\Models\Country::all();
    return response()->json([
	  'status_code' => 200,
	  'countries' => $countries,
	]);
});


//this code should be at last Please every route above this line
Route::get('add_countries', function() {
	

$countries = json_decode(file_get_contents(dirname(dirname(__FILE__ )). '/countries.json'), true);

 $validSorts = [
            'capital',
            'citizenship',
            'country-code',
            'currency',
            'currency_code',
            'currency_sub_unit',
            'full_name',
            'iso_3166_2',
            'iso_3166_3',
            'name',
            'region-code',
            'sub-region-code',
            'eea',
            'calling_code',
            'currency_symbol',
            'flag',
        ];
        $sort = null;
        if (!is_null($sort) && in_array($sort, $validSorts)){
            uasort($countries, function($a, $b) use ($sort) {
                if (!isset($a[$sort]) && !isset($b[$sort])){
                    return 0;
                } elseif (!isset($a[$sort])){
                    return -1;
                } elseif (!isset($b[$sort])){
                    return 1;
                } else {
                    return strcasecmp($a[$sort], $b[$sort]);
                }
            });
        }
        

foreach ($countries as $countryId => $country){
            DB::table('countries')->insert(array(
                'id' => $countryId,
                'capital' => ((isset($country['capital'])) ? $country['capital'] : null),
                'citizenship' => ((isset($country['citizenship'])) ? $country['citizenship'] : null),
                'country_code' => $country['country-code'],
                'currency' => ((isset($country['currency'])) ? $country['currency'] : null),
                'currency_code' => ((isset($country['currency_code'])) ? $country['currency_code'] : null),
                'currency_sub_unit' => ((isset($country['currency_sub_unit'])) ? $country['currency_sub_unit'] : null),
                'currency_decimals' => ((isset($country['currency_decimals'])) ? $country['currency_decimals'] : null),
                'full_name' => ((isset($country['full_name'])) ? $country['full_name'] : null),
                'iso_3166_2' => $country['iso_3166_2'],
                'iso_3166_3' => $country['iso_3166_3'],
                'name' => $country['name'],
                'region_code' => $country['region-code'],
                'sub_region_code' => $country['sub-region-code'],
                'eea' => (bool)$country['eea'],
                'calling_code' => $country['calling_code'],
                'currency_symbol' => ((isset($country['currency_symbol'])) ? $country['currency_symbol'] : null),
                'flag' =>((isset($country['flag'])) ? $country['flag'] : null),
            ));
        }

});