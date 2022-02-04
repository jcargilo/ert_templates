<?php namespace App\Http\Controllers\Store;
 
use App\Services\Validators\ProductValidator;
use App\Models\Occasion as Occasion;
use App\Models\Product as Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use TakeoffDesignGroup\CMS\Http\Controllers\AdminBaseController;

class ProductsController extends AdminBaseController 
{ 
    protected $session = 'admin_products_search';

    public function __construct()
    {
        \View::share('activePage', 'products');
        parent::__construct();   
    }

    public function index()
    {
        $records = new LengthAwarePaginator([], 0, 10);
        $session = $this->session;
        $filter = session()->has($this->session) && isset(session($this->session)['filter']) ? session($this->session)['filter'] : '';

        return view()->make('admin.store.products.index', compact('records', 'filter', 'session'));
    }

    public function create()
    {
        $product = new Product;
        $occasions = Occasion::orderBy('title')->pluck('title', 'id');
        return view()->make('admin.store.products.edit', compact('product', 'occasions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'sku'				=> 'required|max:255',
            'occasions'			=> 'required',
            // 'image'				=> 'required',
            'alt_text'			=> 'required',
            'price_1_dozen'		=> 'required|numeric',
        ]);

        $product = new Product($request->except('occasions'));
        $product->author = '';
        $product->price_2_dozen = (float)$product->price_2_dozen;
        $product->price_3_dozen = (float)$product->price_3_dozen;
        $product->rank = 0;
        $product->save();

        $this->updateOccasions($product, $request->get('occasions'));
        
        return redirect()->route('products.index')
            ->with('success', "The product <em>{$product->title}</em> has been successfully created.");
    }

    public function edit(Product $product)
    {
        $occasions = Occasion::orderBy('title')->pluck('title', 'id');
        return view()->make('admin.store.products.edit', compact('product', 'occasions'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title'				=> 'required|max:255',
            'sku'				=> 'required|max:255',
            'occasions'			=> 'required',
            'alt_text'			=> 'required',
            'price_1_dozen'		=> 'required|numeric',
        ]);

        $product->fill($request->except('action', 'occasions'));
        $product->price_2_dozen = (float)$product->price_2_dozen;
        $product->price_3_dozen = (float)$product->price_3_dozen;
        $product->save();

        $this->updateOccasions($product, $request->get('occasions'));
        
        return redirect()->route('products.edit', $product->id)
            ->with('success', "The product <em>{$product->title}</em> has been successfully updated.");
    }

    protected function updateOccasions($product, $newOccasions)
    {
        // If all rows are empty, exit update.
        if (empty($newOccasions)) return;

        foreach ($newOccasions as $row => $value)
        {
            $occasion = Occasion::find($value);

            if (!empty($occasion))
            {
                // Get starting rank for next product in occasion.
                $numExistingProducts = count($occasion->products);

                $occasions[$value] = (array(
                    'rank' => ($numExistingProducts + $row)
                ));
            }
        }

        $product->occasions()->sync($occasions);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "The product <em>{$product->title}</em> has been successfully deleted.");
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        $product->update([
            'deleted_at' => null
        ]);

        return redirect()->route('products.index')
            ->with('success', "The product <em>{$product->title}</em> has been successfully restored.");
    }

    /* PRIVATE functions */
    private function displayRecords($data)
    {
        $output = Product::advancedSearch($data ?? NULL);
        $output->input = $data;
        return $output;
    }

    private function setupRows($records)
    {
        return view()->make('admin.store.products.results', compact('records'))->render();
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
    public function removeOccasion()
    {
        // Retrieve product id and occasion id to be removed.
        $id = $_POST['id'];
        $occasion_id = $_POST['occasion_id'];

        // Retrieve product.
        $product = Product::find($id);
        
        // Remove occasion from product.
        $product->occasions()->detach($occasion_id);

        echo json_encode(TRUE);
    }

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
}