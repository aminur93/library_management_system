<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Auth route start*/
Route::group(['prefix' => 'v1/auth'], function (){

    /*login route start*/
    Route::post('/login', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'login']);
    /*login route end*/

    Route::group(['middleware' => 'jwtAuth'], function (){

        /*logout route start*/
        Route::post('/logout', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'logout']);
        /*logout route end*/

        /*check token route start*/
        Route::post('/checkToken', [\App\Http\Controllers\Api\V1\Auth\LoginController::class, 'checkToken']);
        /*check token route end*/
    });
});
/* Auth route end*/

/*Admin route start*/
Route::group(['prefix' => 'v1/admin', 'middleware' => 'jwtAuth'], function (){

    /*check permission route start*/
    Route::get('check-permission', [\App\Http\Controllers\CheckPermissionController::class, 'checkPermission']);
    /*check permission route end*/

    /*user route start*/
    Route::get('/user', [\App\Http\Controllers\Api\V1\Admin\UserController::class, 'index']);
    Route::post('/user', [\App\Http\Controllers\Api\V1\Admin\UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}', [\App\Http\Controllers\Api\V1\Admin\UserController::class, 'edit']);
    Route::put('/user/{id}', [\App\Http\Controllers\Api\V1\Admin\UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [\App\Http\Controllers\Api\V1\Admin\UserController::class, 'destroy']);
    /*user route end*/

    /*role route start*/
    Route::get('/role', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'index']);
    Route::post('/role', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'store']);
    Route::get('/role/{id}', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'edit']);
    Route::put('/role/{id}', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'update']);
    Route::delete('/role/{id}', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'destroy']);
    /*role route end*/

    /*permission route start*/
    Route::get('/permission', [\App\Http\Controllers\Api\V1\Admin\PermissionController::class, 'index']);
    Route::post('/permission', [\App\Http\Controllers\Api\V1\Admin\PermissionController::class, 'store']);
    Route::get('/permission/{id}', [\App\Http\Controllers\Api\V1\Admin\PermissionController::class, 'edit']);
    Route::put('/permission/{id}', [\App\Http\Controllers\Api\V1\Admin\PermissionController::class, 'update']);
    Route::delete('/permission/{id}', [\App\Http\Controllers\Api\V1\Admin\PermissionController::class, 'destroy']);
    /*permission route end*/

    /***menu route start**/
    Route::get('/menu', [\App\Http\Controllers\Api\V1\Admin\MenuController::class, 'index']);
    Route::post('/menu', [\App\Http\Controllers\Api\V1\Admin\MenuController::class, 'store']);
    Route::get('/menu/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuController::class, 'show']);
    Route::put('/menu/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuController::class, 'update']);
    Route::delete('/menu/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuController::class, 'destroy']);
    /***menu route end**/

    /***menu dropdown route start**/
    Route::get('/menu-dropdown', [\App\Http\Controllers\Api\V1\Admin\MenuDropDownController::class, 'index']);
    Route::post('/menu-dropdown', [\App\Http\Controllers\Api\V1\Admin\MenuDropDownController::class, 'store']);
    Route::get('/menu-dropdown/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuDropDownController::class, 'show']);
    Route::put('/menu-dropdown/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuDropDownController::class, 'update']);
    Route::delete('/menu-dropdown/{id}', [\App\Http\Controllers\Api\V1\Admin\MenuDropDownController::class, 'destroy']);
    /***menu dropdown route end**/

    /*author route start*/
    Route::get('/author', [\App\Http\Controllers\Api\V1\Admin\AuthorController::class, 'index']);
    Route::post('/author', [\App\Http\Controllers\Api\V1\Admin\AuthorController::class, 'store'])->name('author.store');
    Route::get('/author/{id}', [\App\Http\Controllers\Api\V1\Admin\AuthorController::class, 'show']);
    Route::put('/author/{id}', [\App\Http\Controllers\Api\V1\Admin\AuthorController::class, 'update'])->name('author.update');
    Route::delete('/author/{id}', [\App\Http\Controllers\Api\V1\Admin\AuthorController::class, 'destroy']);
    /*author route end*/

    /*book route start*/
    Route::get('/book', [\App\Http\Controllers\Api\V1\Admin\BookController::class, 'index']);
    Route::post('/book', [\App\Http\Controllers\Api\V1\Admin\BookController::class, 'store'])->name('book.store');
    Route::get('/book/{id}', [\App\Http\Controllers\Api\V1\Admin\BookController::class, 'show']);
    Route::put('/book/{id}', [\App\Http\Controllers\Api\V1\Admin\BookController::class, 'update'])->name('book.update');
    Route::delete('/book/{id}', [\App\Http\Controllers\Api\V1\Admin\BookController::class, 'destroy']);
    /*book route end*/

    /*member route start*/
    Route::get('/member', [\App\Http\Controllers\Api\V1\Admin\MemberController::class, 'index']);
    Route::post('/member', [\App\Http\Controllers\Api\V1\Admin\MemberController::class, 'store'])->name('member.store');
    Route::get('/member/{id}', [\App\Http\Controllers\Api\V1\Admin\MemberController::class, 'show']);
    Route::put('/member/{id}', [\App\Http\Controllers\Api\V1\Admin\MemberController::class, 'update'])->name('member.update');
    Route::delete('/member/{id}', [\App\Http\Controllers\Api\V1\Admin\MemberController::class, 'destroy']);
    /*member route end*/

    /*borrowed book route start*/
    Route::get('/borrow-book', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'index']);
    Route::post('/borrow-book', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'store'])->name('borrowedBook.store');
    Route::get('/borrow-book/{id}', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'show']);
    Route::put('/borrow-book/{id}', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'update'])->name('borrowedBook.update');
    Route::delete('/borrow-book/{id}', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'destroy']);
    Route::post('/borrow-book/status/{id}', [\App\Http\Controllers\Api\V1\Admin\BorrowedBookController::class, 'changeStatus']);
    /*borrowed book route end*/
});
/*Admin route end*/
