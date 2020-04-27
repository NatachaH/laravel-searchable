# Installation

```
composer require nh/searchable
```

# Controller

In your controller add the Facade accessor:

```
use Nh\Searchable\Search;
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
/**
 * Display a listing of the searched resource.
 * @param lluminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
public function search(Request $request)
{
    // Make a Search Class
    $search = new Search('key', $request->input('search'));

    // For override the redirections
    $search->addRedirection('key','routeName');

    // Get an attribute in Search Class
    $keywords = $search->attribute('text');

    // Make the search query
    // The search can be 'contains', 'start' or 'end'
    // And you can decide if all columns match
    $posts = Post::search($keywords,'contains',false)->get();

    // Display the result
    return view('my.search.view', compact('posts'));
}
```

The Search class will create a session with:
- the key
- the redirections (by default: 'reset' => 'myroute/index' and 'search' => 'myroute/search')
- the attributes

The **$request->input('search')** must be an array of field, like search['text'].

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
  'title', 'subtitle', 'description'
];
```

# Routes

You can reset a Search Session by going on the route **searchable.reset**:

```
route('searchable.reset', ['key' => 'key'])
```

# Views

You can get a Search attribute by:

```
{{ session('key')->attribute('text')}}
```
