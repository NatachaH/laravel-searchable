<?php

// Sortable
Route::get('searchable/reset/{key}', function($key){
    $key = 'search.'.$key;
    $session = session($key);
    $session->destroy();
})->name('searchable.reset');
