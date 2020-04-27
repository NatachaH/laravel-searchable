<?php

// Searchable
Route::middleware('web')->get('searchable/reset/{key}', function($key){
    $key = 'search.'.$key;
    $session = session($key);
    $session->destroy();
})->name('searchable.reset');
