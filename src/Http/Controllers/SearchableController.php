<?php

namespace Nh\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SearchableController extends Controller
{
    /**
     * Reset the search;
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $key)
    {
        $key = 'search.'.$key;
        $session = session($key);
        $session->destroy();
        return;
    }
}
