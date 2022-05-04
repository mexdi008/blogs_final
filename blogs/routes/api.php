<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LoginController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

 Route::group( ['middleware' =>['auth:sanctum']], function(){

    Route::group( ['middleware' =>['role:Admin']], function(){
        Route::get('/blogs/{blogs:uniq_id}',[BlogController::class,'show']);  

        Route::post('/blogs',[BlogController::class,'store']);
        Route::put('/blogs/{uniq_id}', [BlogController::class,'update']);
        Route::resource('/blogs', BlogController::class);
    
    });

    Route::group( ['middleware' =>['role:Admin|Operator']], function(){

        Route::get('/blogs',[BlogController::class,'index']);
        Route::get('/blogs/{blogs:uniq_id}',[BlogController::class,'show']);  
    
    });
 

});

Route::group(['middleware' => ['web']], function () {
Route::get('/login',[LoginController::class,'authenticate']);
});
Route::post('/register',[BlogController::class,'register']);
