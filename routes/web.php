<?php

Route::get('/', function () {
    return view('home');
});

Route::resource('authors', 'AuthorController')->only([
    'index', 'create', 'store'
]);
