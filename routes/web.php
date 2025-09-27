<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

Route::prefix("api")->group(function () {
    // Route::get("/customers", [CustomerController::class, "index"]);
    // Route::post("/customers", [CustomerController::class, "store"]);
});
