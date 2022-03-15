<?php

use App\Http\Controllers\AdminBoatsController;
use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AuthorPostsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\{Auth,Route};

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

Route::get('/link', function () {
    return response()->json(["success" => 1, "meta" => ["title" => "Title", "description" => "fsdfsfds dsfa asd asdas dasdasdasd","image" => ["url" => "https://via.placeholder.com/1151x250"]]]);
});

/*
* Basic auth route
*/
Auth::routes();

/*
* Route for only registrated users
*/
Route::group(['middleware' => ['auth']], function () {

    Route::get('/post/{id}', [PostController::class, 'show'])->name('post');

    Route::get('/posts', [PostController::class, 'index'])->name('posts');
    Route::resource('comments',CommentsController::class,
        [
        'except' => [
            'index',
            'show',
        ]
    ])->parameters(['posts' => 'id']);

    Route::resource('user', UserController::class);

    //Route for only authenticated admin users
    Route::group(['middleware' => ['auth','admin']], function () {
        //Author namespace
        Route::name('author.')->group(function () {
            /*
            * Route for posts handeling
            */
            Route::resource('author/posts', AuthorPostsController::class,
                [
                    'except' => [
                        'show',
                        'restore',
                    ]
                ])->parameters(['posts' => 'id']);

            Route::post('author/post/{id}/restore', [AuthorPostsController::class, 'restore'])
                ->name('posts.restore');
        });

        //Admin namespace
        Route::name('admin.')->group(function () {

            /*
            * Route for posts handeling
            */
            Route::resource('admin/posts', AdminPostsController::class,
                [
                    'except' => [
                        'show',
                        'create',
                        'store',
                    ]
                ])->parameters(['posts' => 'id']);

            Route::post('admin/post/{id}/restore', [AdminPostsController::class,'restore'])
                ->name('posts.restore');

            /*
            * Route for users handeling
            */
            Route::resource('admin/user', AdminUsersController::class,
                [
                    'except' => [
                        'index',
                        'create'
                    ]
                ])->parameters(['user' => 'id']);

            Route::post('admin/user/{id}/restore', [AdminUsersController::class,'restore'])->name('user.restore');

            Route::get('admin/users/', [AdminUsersController::class,'index'])->name('users.index');

            Route::get('admin/users/search/', [AdminUsersController::class,'search'])->name('users.search');

            /*
            * Route for boats handeling
            */
            Route::resource('admin/boats', AdminBoatsController::class,
                [
                    'except' => [
                        'index',
                    ]
                ])->parameters(['boats' => 'id']);

            Route::post('admin/boats/{id}/restore', [AdminBoatsController::class,'restore'])->name('boat.restore');

            Route::get('admin/boats/', [AdminBoatsController::class,'index'])->name('boats.index');

        });
    });

});


Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
