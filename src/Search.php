<?php

namespace Nh\Searchable;

use Route;

class Search
{
    /**
     * Key name of the Search.
     * By default search.key
     * @var string
     */
    public $key;

    /**
     * Routes for redirections if there is (or not) a Search Session.
     * @var array
     */
    public $redirections;

    /**
     * Array of the searched attributes.
     * @var array
     */
    public $attributes;

    /**
     * Create a new search instance.
     *
     * @return Nh\Searchable\Search
     */
    public function __construct($key, $request, $redirections = [])
    {
        $this->key = 'search.'.$key;
        $this->defineRedirections($redirections);
        $this->defineAttributes($request);

        if(empty($this->attributes))
        {
            $this->destroy();
        }

        return $this;
    }

    /**
     * Get an attribute value by key.
     * @param  string $key
     * @return string
     */
    public function attribute($key)
    {
        return !empty($this->attributes) && array_key_exists($key,$this->attributes) ? $this->attributes[$key] : null;
    }

    /**
     * Get a redirection value by key.
     * @param  string $key
     * @return string
     */
    public function redirection($key)
    {
        return !empty($this->redirections) && array_key_exists($key,$this->redirections) ? $this->redirections[$key] : null;
    }

    /**
     * Define the attributes by request if != of the current session.
     * And set the new session
     * @param  array $request
     * @return void
     */
    protected function defineAttributes($request)
    {
        // Get method
        $method = request()->getMethod();

        // Clean the Request array of empty values.
        $request = is_null($request) ? null : array_filter($request);

        // Get the current Session if exist.
        $sessionAttributes = session()->exists($this->key) ? session($this->key)->attributes : null;

        // Define the attribute
        switch ($method) {
          case 'POST':
            $this->attributes = $request;
            session()->put($this->key, $this);
            break;

          default:
            $this->attributes = $sessionAttributes;
            break;
        }
    }

    /**
     * Define the default redirections route name.
     * @return void
     */
    protected function defineRedirections(array $redirections = [])
    {
        $current = Route::currentRouteName();
        $this->redirections['reset'] = array_key_exists('reset',$redirections) ? $redirections['reset'] : str_replace('search','index',$current);
        $this->redirections['search'] = array_key_exists('search',$redirections) ? $redirections['search']: $current;
    }

    /**
     * Remove the session and redirect if needed.
     * @return void
     */
    public function destroy()
    {
        session()->forget($this->key);

        $redirection = $this->redirection('reset') ?? null;

        if($redirection)
        {
            if(Route::has($redirection))
            {
                return redirect()->route($redirection)->send();
            } else {
                return redirect($redirection)->send();
            }
        }
    }

}
