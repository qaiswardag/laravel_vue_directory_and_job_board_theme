<?php

use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

// test - start
// test - start
// test - start
// Route::post("/docs-users", function () {
Route::post("/docs-users", function () {
    //
    //
    // return response()->json(
    //     "My Success message from backend. Worked fine.",
    //     200
    // );
    //
    return response(
        "My message from backend. Gave error earlier 1.",
        200
    )->header("Content-Type", "text/plain");
    //
    //
    //
    //
    //
    //
    // Return errors and error text in JSON format
    // Return errors and error text in JSON format
    // Return errors and error text in JSON format
    // Return errors and error text in JSON format
    //
    //
    //
    // return response()
    //     // convert text to json
    //     ->json("Hello world.", 500)
    //     ->header("Content-Type", "application/json");
    //
    //
    //
    //
    // return response()->json(["This is an array of a single error."], 401);
    //
    //
    //
    //
    //
    // return response()->json(
    //     [
    //         "fejl_en" => "We are unable to send you users at current moment.",
    //         "fejl_to" => "Please try again.",
    //         "fejl_tre" => "Or contact us.",
    //         "fejl_fem" => ["Check check check.", "La la la."],
    //     ],
    //     401
    // )
    // ->header("Content-Type", "application/json");
    //
    //
    //
    // Return users
    return response(
        [
            "users" => User::latest()
                ->take(10)
                ->get(),
        ],
        200
    );
    //
    // ->header("Content-Type", "text/plain");
});
// test - start
// test - start
// test - start
