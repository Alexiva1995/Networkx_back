<?php

use App\Http\Controllers\PackageMembershipController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\UserController;
use App\Services\PagueloFacilService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'DONE'; //Return anything
});

Route::get('/', function () {
    return view('welcome');
});

/* Route::get('test', function (){
    return view('mails.verification');
}); */
