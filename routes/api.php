<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Register route
    Route::post("register", [AuthController::class, "register"]);
    // Login route
    Route::post("login", [AuthController::class, "login"]);
    // Logout route
    Route::post("logout", [AuthController::class, "logout"]);

});

// User Routes - These require JWT authentication
Route::middleware('auth:api')->group(function () {
    // Route to get authenticated user data
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    // Get all users route (only accessible by authenticated users)
    Route::get("users", [HomeController::class, "index"]);
});

Route::apiResource("category",CategoryController::class);
