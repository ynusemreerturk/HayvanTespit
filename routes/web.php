<?php

use App\Http\Controllers\Admin\ActionsController;
use App\Http\Controllers\Admin\CameraController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\User\ActionsUserController;
use App\Http\Controllers\User\CameraUserController;
use App\Http\Controllers\User\UserPageController;
use Illuminate\Support\Facades\Route;

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



Route::prefix('')->group(function() {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login.post');
});
Route::middleware(['auth'])->group(function () {
    Route::get('user/dashboard', [UserPageController::class, 'index'])->name('user.dashboard');
    Route::prefix('aksiyonlar/user')->group(function () {
        Route::get('/index',[ActionsUserController::class, 'index'])->name('user.action.index');
        Route::get('/fetch',[ActionsUserController::class, 'fetch'])->name('user.action.fetch');
        Route::get('/get/{id}',[ActionsUserController::class, 'get'])->name('user.action.get');
        Route::post('/update',[ActionsUserController::class, 'update'])->name('user.action.update');
        Route::get('/dogruluk-oranlari', [ActionsUserController::class, 'getTumDogrulukYanlis'])->name('action.user.dogruluk');
        Route::get('/kamera-dogruluk-yanlis', [ActionsUserController::class, 'getKameraDogrulukYanlis'])->name('action.user.kamera.dogruluk');
        Route::get('/hayvan-tipi-dogruluk-yanlis', [ActionsUserController::class, 'getHayvanTipiDogrulukYanlis'])->name('action.user.hayvan.dogruluk');
    });
    Route::prefix('kameralar/user')->group(function () {
        Route::get('/index',[CameraUserController::class, 'index'])->name('user.camera.index');
        Route::get('/fetch',[CameraUserController::class, 'fetch'])->name('user.camera.fetch');
    });
    Route::middleware('islogin')->group(function(){
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        });
        Route::prefix('iletisimler')->group(function () {
            Route::get('/index',[ContactController::class, 'index'])->name('contacts.index');
            Route::get('/fetch',[ContactController::class, 'fetch'])->name('contacts.fetch');
            Route::get('/list',[ContactController::class, 'list'])->name('contacts.list');
            Route::post('/check',[ContactController::class, 'check'])->name('contacts.check');
            Route::post('/uncheck',[ContactController::class, 'uncheck'])->name('contacts.uncheck');
            Route::get('/detail',[ContactController::class, 'detail'])->name('contacts.detail');
            Route::get('/createIndex',[ContactController::class, 'createIndex'])->name('contacts.create.index');
            Route::post('/createPost',[ContactController::class, 'createPost'])->name('contacts.create.post');
            Route::post('/delete',[ContactController::class, 'delete'])->name('contacts.delete');
        });
        Route::prefix('user')->group(function () {
            Route::get('/index',[UserController::class, 'index'])->name('user.index');
            Route::get('/createIndex',[UserController::class, 'createIndex'])->name('user.create.index');
            Route::post('/createPost',[UserController::class, 'createPost'])->name('user.create.post');
            Route::get('/getCity',[UserController::class,'getCity'])->name('get.city');
            Route::get('/getTown',[UserController::class,'getTown'])->name('get.town');
            Route::get('/getNeighbourhood',[UserController::class,'getNeighbourhood'])->name('get.neighbourhood');
            Route::get('/getStreet',[UserController::class,'getStreet'])->name('get.street');
            Route::get('/fetch',[UserController::class, 'fetch'])->name('user.fetch');
            Route::post('/update',[UserController::class, 'update'])->name('user.update');
            Route::post('/delete',[UserController::class, 'delete'])->name('user.delete');
            Route::get('/userget',[UserController::class, 'userget'])->name('user.get');
        });
        Route::prefix('kameralar')->group(function () {
            Route::get('/index',[CameraController::class, 'index'])->name('camera.index');
            Route::get('/fetch',[CameraController::class, 'fetch'])->name('camera.fetch');
            Route::post('/create',[CameraController::class, 'create'])->name('camera.create');
            Route::get('/edit/{id}',[CameraController::class, 'edit'])->name('camera.edit');
            Route::post('/update',[CameraController::class, 'update'])->name('camera.update');
            Route::post('/delete',[CameraController::class, 'delete'])->name('camera.delete');
        });
        Route::prefix('aksiyonlar')->group(function () {
            Route::get('/index',[ActionsController::class, 'index'])->name('action.index');
            Route::get('/fetch',[ActionsController::class, 'fetch'])->name('action.fetch');
            Route::get('/get/{id}',[ActionsController::class, 'get'])->name('action.get');
            Route::post('/update',[ActionsController::class, 'update'])->name('action.update');
            Route::get('/dogruluk-oranlari', [ActionsController::class, 'getTumDogrulukYanlis'])->name('action.dogruluk');
            Route::get('/kamera-dogruluk-yanlis', [ActionsController::class, 'getKameraDogrulukYanlis'])->name('action.kamera.dogruluk');
            Route::get('/hayvan-tipi-dogruluk-yanlis', [ActionsController::class, 'getHayvanTipiDogrulukYanlis'])->name('action.hayvan.dogruluk');

        });
    });
    Route::get('/logOut', [LoginController::class, 'logOut'])->name('logOut');
});
Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::get('/about', [HomepageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/createPost',[ContactController::class, 'createPost'])->name('contacts.create.post');


