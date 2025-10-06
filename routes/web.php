<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;

Route::get("/", function () {
    return view("welcome");
});

Route::prefix("api")->group(function () {
    // Route::get("/customers", [CustomerController::class, "index"]);
    // Route::post("/customers", [CustomerController::class, "store"]);
});

Route::resource('staffs', StaffController::class);