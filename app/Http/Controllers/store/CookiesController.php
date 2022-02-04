<?php namespace App\Http\Controllers\Store;

use App\Models\Cookie as Cookie;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use TakeoffDesignGroup\CMS\Http\Controllers\AdminBaseController;

class CookiesController extends AdminBaseController
{
    protected $session = 'admin_cookies_search';

    public function __construct()
    {
        view()->share('activePage', 'cookies');
        parent::__construct();   
    }

    public function index()
    {
        $records = new LengthAwarePaginator([], 0, 10);
        $session = $this->session;
        $filter = session()->has($this->session) && isset(session($this->session)['filter']) ? session($this->session)['filter'] : '';

        return view()->make('admin.store.cookies.index', compact('records', 'filter', 'session'));
    }

    public function create()
    {
        $cookie = new Cookie;
        view()->share('loadEditor', true);
        return view()->make('admin.store.cookies.edit', compact('cookie'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'thumb'				=> 'required',
            'image'				=> 'required',
            'sku'				=> 'required|max:50',
        ]);

        $cookie = new Cookie($request->all());
        $cookie->author = '';
        $cookie->rank = Cookie::getNextRank();
        $cookie->featured = $cookie->featured ?? 0;
        $cookie->save();
         
        return redirect()->route('cookies.edit', $cookie)
            ->with('success', "The cookie <em>{$cookie->title}</em> has been successfully published.");
    }

    public function edit(Cookie $cookie)
    {
        view()->share('loadEditor', true);

        return view()->make('admin.store.cookies.edit')
            ->with('cookie', $cookie);
    }

    public function update(Request $request, Cookie $cookie)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'sku'				=> 'required|max:50',
        ]);
 
        $cookie->fill($request->all());

        // If cookie is being featured, remove previously featured cookie for the intended column.
        if ($cookie->featured > 0) {
            Cookie::clearFeaturedColumn($cookie->featured);
        }

        $cookie->save();
        
        return redirect()->route('cookies.edit', $cookie->id)
            ->with('success', "The cookie <em>{$cookie->title}</em> has been successfully updated.");
    }

    public function destroy($id)
    {
        $cookie = Cookie::find($id);
        if (!isset($cookie)) {
            return redirect()->route('cookies.index')->with('error', 'That cookie either does not exist or has been deleted.');
        }

        $cookie->delete();

        return redirect()->route('cookies.index')
            ->with('success', "The <em>{$cookie->title}</em> cookie has been successfully deleted.");
    }

    public function restore($id)
    {
        $cookie = Cookie::withTrashed()->find($id);
        $cookie->update([
            'deleted_at' => null
        ]);

        return redirect()->route('cookies.index')
            ->with('success', "The cookie <em>{$cookie->title}</em> has been successfully restored.");
    }
    
    /* PRIVATE functions */
    private function displayRecords($data)
    {
        $output = Cookie::advancedSearch($data ?? NULL);
        $output->input = $data;
        return $output;
    }

    private function setupRows($records)
    {
        return view()->make('admin.store.cookies.results', compact('records'))->render();
    }

    private function loadRecords($input = NULL)
    {
         $data = $this->displayRecords($input ?: request()->all());
         $records = new LengthAwarePaginator($data->items->toArray(), $data->totalItems, $data->limit);
         $data->filtered = $this->setupRows($data->items);
         $data->lastPage = $records->lastPage();
         return $data;
    }

     /**********************************************************************************************
      *
      *  AJAX Functions
      *
      **********************************************************************************************/ 
    public function loadSaved()
    {
        if (!session()->has($this->session))
            return response()->json(false);

        $data = session()->get($this->session);
        return response()->json($this->loadRecords($data));
    }

    public function filter()
    {
        // Save search criteria to session
        session()->put($this->session, request()->all());

        return response()->json($this->loadRecords());
    }

    public function reset()
    {
        session()->forget($this->session);
    }

    public function sort()
    {
        // Parse sort order.
        $order = explode(',', $_POST['order']);
        
        // Resort slides.
        for ($i = 0; $i < count($order); $i++)
        {
            $cookie = Cookie::find($order[$i]);
            $cookie->rank = $i + 1;
            $cookie->save();
        }
    }
}