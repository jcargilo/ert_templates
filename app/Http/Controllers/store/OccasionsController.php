<?php namespace App\Http\Controllers\Store;
 
use App\Models\Product as Product;
use App\Models\Cookie as Cookie;
use App\Models\Occasion as Occasion;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use TakeoffDesignGroup\CMS\Http\Controllers\AdminBaseController;

class OccasionsController extends AdminBaseController
{
    protected $session = 'admin_occasions_search';

    public function __construct()
    {
        view()->share('activePage', 'occasions');
        parent::__construct();   
    }

    public function index()
    {
        $records = new LengthAwarePaginator([], 0, 10);
        $session = $this->session;
        $filter = session()->has($this->session) && isset(session($this->session)['filter']) ? session($this->session)['filter'] : '';

        return view()->make('admin.store.occasions.index', compact('records', 'filter', 'session'));
    }

    public function create()
    {
        $occasion = new Occasion;
        $cookies = Cookie::active()->get();
        view()->share('loadEditor', true);
        return view()->make('admin.store.occasions.edit', compact('occasion', 'cookies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'seo_title'			=> 'max:255',
            'seo_keywords'		=> 'max:500',
            'seo_description'	=> 'max:1000',
        ]);

        $occasion         = new Occasion($request->except('cookies'));
        $occasion->author = '';
        $occasion->rank   = 100;
        $occasion->featured = $occasion->featured ?? 0;

        // If occasion is being set to seasonal or limited, make sure that exactly 1 dozen cookies have been specified.
        if (!empty($error = $occasion->setAssortment($request->get('cookies')))) {
            return redirect()->back()->with('error', $error);
        }

        $occasion->save();

        return redirect()->route('occasions.edit', $occasion->id)
            ->with('success', "The occasion <em>{$occasion->title}</em> has been successfully published.");
    }

    public function show($id)
    {
        // Make sure occasion exists.
        $occasion = Occasion::find($id);
                                    
        if (!isset($occasion))
        {
            Notification::error('That occasion either does not exist or has been deleted.');
            return redirect()->route('admin.occasions.index');
        }

        return \View::make('admin.occasions.show')
            ->with('occasion', $occasion);
    }

    public function edit($id)
    {
        // Make sure occasion exists.
        $occasion = Occasion::withTrashed()->find($id);
        
        if (!isset($occasion)) {
            return redirect()->route('occasions.index')->with('error', 'That occasion either does not exist or has been deleted.');
        }

        $cookies = Cookie::active()->get();
        view()->share('loadEditor', true);

        return view()->make('admin.store.occasions.edit', compact('occasion', 'cookies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'seo_title'			=> 'max:255',
            'seo_keywords'		=> 'max:500',
            'seo_description'	=> 'max:1000',
        ]);
        
        // Make sure occasion exists.
        $occasion = Occasion::withTrashed()->find($id);
        if (!isset($occasion)) {
            return redirect()->route('occasions.index')->with('error', 'That occasion either does not exist or has been deleted.');
        }
        
        // Determine if this occasion has active products before allowing 
        $isPublishing = !$occasion->published && $request->get('published') == 1;
        $canPublish = $occasion->products(true)->count() > 0;
        
        if (!$isPublishing || ($isPublishing && $canPublish))
        {
            $data = $request->except('cookies');
            $occasion->fill($data);

            // If occasion is being set to seasonal or limited, make sure that exactly 1 dozen cookies have been specified.
            if (!empty($error = $occasion->setAssortment($request->get('cookies')))) {
                return back()->withInput()->with('error', $error);
            }
            
            // If occasion is being featured, remove previously featured occasion for the intended column.
            $occasion->featured = $occasion->featured ?? 0;

            $occasion->save();

            return redirect()->route('occasions.edit', $occasion->id)
                ->with('success', "The occasion <em>{$occasion->title}</em> has been successfully updated.");
        }
        else if ($isPublishing && !$canPublish)
        {
            return back()->withInput()
                ->with('error', "The occasion <em>{$occasion->title}</em> cannot be made public yet because it does not currently have any <i>active</i> products assigned to it.  An occasion must have at least 1 active product to be made public.");
        }
    }

    public function destroy($id)
    {
        $occasion = Occasion::find($id);
        if (!isset($occasion))
        {
            return redirect()->route('occasions.index')
                ->with('error', 'That occasion either does not exist or has been deleted.');
        }

        $occasion->delete();

        return redirect()->route('occasions.index')
            ->with('success', "The <em>{$occasion->title}</em> occasion has been successfully deleted.");
    }

    public function restore($id)
    {
        $occasion = Occasion::withTrashed()->find($id);
        $occasion->update([
            'deleted_at' => null
        ]);

        return redirect()->route('occasions.index')
            ->with('success', "The occasion <em>{$occasion->title}</em> has been successfully restored.");
    }

     /* PRIVATE functions */
     private function displayRecords($data)
     {
         $output = Occasion::advancedSearch($data ?? NULL);
         $output->input = $data;
         return $output;
     }
 
     private function setupRows($records)
     {
         return view()->make('admin.store.occasions.results', compact('records'))->render();
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

    public function sort(Request $request, Occasion $occasion)
    {
        if (!empty($occasion))
        {
            $order = explode(',', $request->get('order'));

            // Clear previous associations so we can re-order them.
            $occasion->products()->sync(array());

            // Resort products within occasion.
            for ($i = 0; $i < count($order); $i++)
            {
                $product_id = $order[$i];
                $rank = $i + 1;

                $occasion->products()->attach(array($product_id => array('rank' => $rank)));                
            }
        }
    }
}