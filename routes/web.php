<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/users-page', 'users.index')->name('users.page.index');
Route::view('/users-page/create', 'users.create')->name('users.page.create');
Route::view('/users-page/{id}/edit', 'users.edit')->whereNumber('id')->name('users.page.edit');
Route::view('/users-page/{id}', 'users.show')->whereNumber('id')->name('users.page.show');