<?php

// Searchable
Route::middleware('web')->get('searchable/reset/{key}', '\Nh\Searchable\Http\Controllers\SearchableController')->name('searchable.reset');
