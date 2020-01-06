<?php

Route::get('/', function () {
    return view('welcome');
});

Route::resource('authors', 'AuthorController')->only([
    'index', 'create', 'store'
]);
