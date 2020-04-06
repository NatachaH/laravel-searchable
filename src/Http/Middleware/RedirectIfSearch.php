<?php

namespace Nh\Searchable\Middleware;

use Closure;

class RedirectIfSearch
{
    /**
     * Handle an incoming request, check if there is a search session and redirect in the search page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $key Session key name
     * @param  string  $redirection Route name for redirection
     * @return mixed
     */
    public function handle($request, Closure $next, $key)
    {
        $key = 'search.'.$key;

        if(session()->exists($key))
        {
            $session = session($key);
            $redirection = $session->redirections['search'];
            if(Route::has($redirection))
            {
                return redirect()->route($redirection);
            }
        }

        return $next($request);
    }
}
