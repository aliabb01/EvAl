<?php

// use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\PostMediaController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

// Force https use
if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user/isadmin', function(Request $request) {
    return $request->user()->hasRole('administrator');
});

/**
 * Public routes
 */

// User API routes
// Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/user/{id}', [UserController::class, 'show']);
// Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::post('/user/{id}/upload-image', [UserController::class, 'uploadAvatar']);
Route::get('/user/{id}/avatar', [UserController::class, 'getAv']);
// Route::put('/user/{id}/avatar', [UserController::class, 'updateAvatar']);

// Posts API routes
// CRUD
Route::get('/posts', [PostController::class, 'index']);
// Route::post('/posts', [PostController::class, 'store']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/posts/custom', [PostController::class, 'showSelected']);
Route::put('/post/{id}', [PostController::class, 'update']);


// Post Media
Route::get('/post/{id}/media', [PostMediaController::class, 'show']);

// Get vip posts
Route::get('/posts/vip', [PostController::class, 'vipPosts']);

// Get vip posts by date
Route::get('/posts/vip/tarix', [PostController::class, 'vipPostsByPeriod']);

// Get posts from agencies
Route::get('posts/agentlikler', [PostController::class, 'latestAgencyPosts']);

// Agencies API routes


Route::get('/agency/{id}', [AgencyController::class, 'show']);
Route::put('/agency/{id}', [AgencyController::class, 'update']);
Route::delete('/agency/{id}', [AgencyController::class, 'destroy']);

// Admins API routes
// Route::get('/admins', [AdminController::class, 'index']);
// Route::post('/admins', [AdminController::class, 'store']);
// Route::get('/admin/{id}', [AdminController::class, 'show']);
// Route::put('/admin/{id}', [AdminController::class, 'update']);
// Route::delete('/admin/{id}', [AdminController::class, 'destroy']);

// Flag API routes


Route::get('/flag/{id}', [FlagController::class, 'show']);


/**
 * Relationship routes (Hierarchical)
 */

// User-Post route (Show posts of a user)
Route::get('user/{id}/posts', [UserController::class, 'showUserPosts']);

// User-Agency route (Show agency of a user)
Route::get('user/{id}/agency', [UserController::class, 'showUserAgency']);

// Agency-User route (Show users of an agency)
Route::get('agency/{id}/users', [AgencyController::class, 'showAgencyUsers']);

// User-Flag route (Show flags of a user)
Route::get('user/{id}/flags', [UserController::class, 'showUserFlags']);

// Post-Flag route (Show flags of a post)
Route::get('post/{id}/flags', [PostController::class, 'showPostFlags']);

// Post-PostImage route (Show images of a post)
Route::get('post/{id}/images', [PostImageController::class, 'showPostOfImage']);

// Search
Route::post('/search', [PostController::class, 'searchPost']);

// Auth 
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

/**
 * Protected routes
 */

Route::group(['middleware' => ['auth:sanctum']], function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/refresh', [AuthController::class, 'refresh']);

    // Route::post('/posts', [PostController::class, 'store']);

    Route::put('/user/{id}', [UserController::class, 'update']);

    Route::put('/user/{id}/avatar', [UserController::class, 'updateAvatar']);

    Route::get('/users', [UserController::class, 'index']);

    // Posts
    Route::patch('/post/{id}/increase-score', [PostController::class, 'increaseScore']);
    Route::patch('/post/{id}/make-vip', [PostController::class, 'makeVip']);

    // Verify email
    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail']);
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
});

/**
 * User Group Functions
 */
Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/upload', [UploadController::class, 'store']);

    // Flag
    Route::post('/flags', [FlagController::class, 'store']);
});

/**
 * Moderator Group Functions
 */
Route::group(['middleware' => ['auth:sanctum', 'role:moderator']], function () {
    Route::post('/agencies', [AgencyController::class, 'store']);
});

/**
 * Administrator Group Functions
 */
Route::group(['middleware' => ['auth:sanctum', 'role:administrator']], function () {
    Route::get('/flags', [FlagController::class, 'index']);
    Route::put('/flag/{id}', [FlagController::class, 'update']);
    Route::delete('/flag/{id}', [FlagController::class, 'destroy']);

    // Agency
    Route::get('/agencies', [AgencyController::class, 'index']);

    // Post
    Route::delete('/post/{id}', [PostController::class, 'destroy']);
});

/**
 * Method not allowed error handling
 */

Route::get('/{any}', function() {
    return response()->json(['Not Found (404)' => 'This page does not exist'], 404, [], JSON_PRETTY_PRINT);
});

Route::post('/{any}', function() {
    return response()->json(['Bad method (405)' => 'This route does not support POST method'], 405, [], JSON_PRETTY_PRINT);
});

Route::put('/{any}', function() {
    return response()->json(['Bad method (405)' => 'This route does not support PUT method'], 405, [], JSON_PRETTY_PRINT);
});

Route::delete('/{any}', function() {
    return response()->json(['Bad method (405)' => 'This route does not support DELETE method'], 405, [], JSON_PRETTY_PRINT);
});