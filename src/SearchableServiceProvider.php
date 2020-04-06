<?php
namespace Nh\Searchable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

use Nh\Searchable\Search;

class SearchableServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('search',function(){
            return new Search();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        // MIDDLEWARES
        $router->aliasMiddleware('search', \Nh\Searchable\Middleware\RedirectIfSearch::class);


    }
}
