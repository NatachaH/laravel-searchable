# Installation

```
composer require nh/searchable
```

# Controller

In your controller add the Facade accessor:

```
use Nh\Searchable\Search
```

Next, add the middleware **search** in the **__construct()** method:
**The search middleware will automatically redirect if there is a search session**

```
/**
 * Instantiate a new controller instance.
 *
 * @return void
 */
public function __construct()
{
    $this->middleware('search:key')->only('index');
}
```

Finnaly, add a **search()** method:

```
public function search()
{
    // Make a Search Class
    $search = Search::new('key', $request->input('search'));

    // For override the redirections
    $search->addRedirection('key','routeName');

    // Get an attribute in Search Class
    $keywords = $search->attribute('text');

    // Make the search query
    $posts = Post::search($keywords,'contains',true)->get();

    // Display the result
    return view('my.search.view', compact('posts'));
}
```

# Model

Add the **Searchable** trait to your model:

```
use Nh\Searchable\Traits\Searchable;

use Searchable;
```

And you can set the columns where to make the search:

```
/**
 * The searchable columns.
 *
 * @var array
 */
protected $searchable = [
  'title'
];
```
