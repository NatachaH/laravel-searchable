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
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
public function search(Request $request)
{
    // Make a Search Class
    $search = new Search('key', $request->input('search'));

    // Get an attribute in Search Class
    $keywords = $search->attribute('text');

    // Make the search query
    $posts = Post::search($keywords,'contains',false)->get();

    // Display the result
    return view('my.search.view', compact('posts'));
}
```

The Search class will create a session with:
- the key (should be your model ex: posts)
- the redirections (by default: 'reset' => 'myroute/index' and 'search' => 'myroute/search')
- the attributes

The **$request->input('search')** must be an array of field, like search['text'].

You can override the redirections with an array:

```
$search = new Search('key', $request->input('search'), [
  'reset' => 'custom.redirection',
  'search' => 'custom.redirection'
]);
```

You can access to a Search redirection by:

```
session('search.key')->redirection('reset');
```

You can access to a Search attribute by:

```
session('search.key')->attribute('text');
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
  'title', 'subtitle', 'description'
];
```

Then you can make a search query as:
*The search can be 'contains', 'start' or 'end'*
*And you can decide if all columns match by setting true/false*

```
Post::search('my keyword','contains',false)->get();
```

You can also search between 2 values:
*Default values are 0 to 99999999999999999999*

```
Post::searchBetween('mycolumn',10,100)->get();    // Retrieve where between 10 and 100
Post::searchBetween('mycolumn',null,100)->get();  // Retrieve where under or equal 100
Post::searchBetween('mycolumn',100,null)->get();  // Retrieve where greater or equal than 100
Post::searchBetween('mycolumn',0,0)->get();       // Retrieve where is 0 or null
```

# Routes

You can reset a Search Session by going on the route **searchable.reset**:

```
route('searchable.reset', ['key' => 'key'])
```
