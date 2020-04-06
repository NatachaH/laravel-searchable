# Installation


# Controller

Add this line to automatic redirect if a search session exist:

```
/**
 * Instantiate a new controller instance.
 *
 * @return void
 */
public function __construct()
{
    $this->middleware('search:keyname')->only('index');
}
```

Add This line to save the search as session:

```
use Nh\Searchable\Search

// Make a Search Class
$search = Search::new('keyname', $request->input('search'));

// Get an attribute in Search Class
$keyword = $search->attribute('text');

// For overide the redirections
$search->addRedirection('key','routeName');
```
