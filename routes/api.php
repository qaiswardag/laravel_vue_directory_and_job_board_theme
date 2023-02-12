<?php

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
Route::get("/docs-users", function () {
    // return response()->json(
    //     [
    //         "fejl_en" => "We are unable to send you users at current moment.",
    //         "fejl_to" => "Please try again.",
    //         "fejl_tre" => "Or contact us.",
    //         // "fejl_fem" => ["Check check check.", "La la la."],
    //     ],
    //     401
    // );
    //
    //
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

// Route::get("/test-me", function () {
//     // user
//     // return User::where("id", 1)->get();
//     // team
//     // team
//     // team
//     // $posts = Team::latest()
//     //     ->where("id", 5)
//     //     // ->posts() // fejler
//     //     // ->paginate(2);
//     //     ->get();
//     // return $posts;

//     //
//     //
//     //
//     //
//     //
//     $posts = Auth::user()->posts();
//     return $posts;
// });
