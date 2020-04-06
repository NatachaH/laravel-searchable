<?php

namespace Nh\Searchable;


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
     * @return void
     */
    public function __construct($key, $request)
    {
        $this->key = 'search.'.$key;
        $this->redirection = $redirection;
        $this->defineRedirections();
        $this->defineAttributes($request);
        if(empty($this->attributes))
        {
            $this->destroy();
        }
    }

    /**
     * Get an attribute value by key.
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        return !empty($this->attributes) && array_key_exists($key,$this->attributes) ? $this->attributes[$key] : null;
    }

    /**
     * Define the attributes by request if != of the current session.
     * And set the new session
     * @param  array $request
     * @return void
     */
    protected function defineAttributes($request)
    {
        // Clean the Request array of empty values.
        $request = is_null($request) ? null : array_filter($request);

        // Get the current Session if exist.
        $sessionAttributes = session()->exists($this->key) ? session($this->key)->attributes : null;

        // If the Request is not null and is different from the current session
        if(!is_null($request) && $request !== $sessionAttributes)
        {
            $this->attributes = $request;
            session()->put($this->key, $this);
        }
        else
        {
            $this->attributes = $sessionAttributes;
        }
    }

    /**
     * Define the default redirections route name.
     * And set the new session
     * @param  array $routes
     * @return void
     */
    protected function defineRedirections()
    {
        $current = Route::currentRouteName();
        $this->redirections['reset'] = str_replace('search','index',$current);
        $this->redirections['search'] = $current;
    }

    /**
     * For overide the redirections route name.
     * And set the new session
     * @param  array $routes
     * @return void
     */
    public function addRedirection($key,$route)
    {
        $this->redirections[$key] = $route;
        session()->push($this->key.'.redirections', $this->redirections);
    }

    /**
     * Remove the session and redirect if needed.
     */
    protected function destroy()
    {
        session()->forget($this->key);

        if(!is_null($this->redirection))
        {
          return redirect()->route($this->redirections['reset'])->send();
        }
    }

}
