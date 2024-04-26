<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/create', [UserController::class, 'create']);
Route::post('/update', [UserController::class, 'update']);
Route::post('/list', [UserController::class, 'index']);

// Json response format

// {
//     "firstname": "Code",
//     "lastname": "Flow",
//     "email": "codeflow110101@gmail.com",
//     "password": "123456",
//     "address": "US"
// }


