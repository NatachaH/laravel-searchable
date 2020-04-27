<?php

// Sortable
Route::get('searchable/reset/{key}', function($key){
    $key = 'search.'.$key;
    $session = session($key);
    $redirection = $session->redirections['search'];
    session()->forget($key);
    if(Route::has($redirection))
    {
        return redirect()->route($redirection);
    }
})->name('searchable.reset');
