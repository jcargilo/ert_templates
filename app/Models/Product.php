<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB, StdClass, Helpers;

class Product extends \Eloquent
{
	use SoftDeletes;

	protected $table = 'products';
	protected $guarded = ['id'];
	protected $dates = ['deleted_at'];

	public function setPrice1DozenAttribute($value)
	{
		$value = str_replace('$', '', $value);
		$value = str_replace(',', '', $value);
		$this->attributes['price_1_dozen'] = $value;
	}

	public function setPrice2DozenAttribute($value)
	{
		$value = str_replace('$', '', $value);
		$value = str_replace(',', '', $value);
		$this->attributes['price_2_dozen'] = $value;
	}

	public function setPrice3DozenAttribute($value)
	{
		$value = str_replace('$', '', $value);
		$value = str_replace(',', '', $value);
		$this->attributes['price_3_dozen'] = $value;
	}

	// Relationships
	public function occasions()
	{
		return $this->belongsToMany('App\Models\Occasion', 'products_in_occasions')
			->withPivot('rank');
	}

    /**
	 * Get results by page including filtering and sorting
	 *
	 * @param int $page
	 * @param int $limit
	 * @param string $field
	 * @param string $direction
	 * @return StdClass
	 */
	public static function advancedSearch($data = NULL)
	{
		$page = $data['page'] ?? 1;
		$limit = $data['limit'] ?? 10;
		$field = $data['field'] ?? '';
		$direction = $data['direction'] ?? '';

		$results = new StdClass;
		$results->page = $page;
		$results->limit = $limit;
		$results->totalItems = 0;
		$results->items = array();

		if ($field === '' || $field === NULL)
			$field = 'products.created_at';
		else
			$field = 'products.'.$field;

		if ($direction === '' || $direction === NULL)
			$direction = 'desc';

		// Setup default queries.
		$query = Product::withTrashed()->with('occasions')
			->filter($data);

		// Add sorting
		$query->orderByRaw("if({$field} is null,1,0),{$field} {$direction}");

		// Retrieve count.
		$results->totalItems = $query->count();

		$query->skip($limit * ($page - 1))
				->take($limit);

		// obtain query
        $results->items = $query->get();

		return $results;
	}

	public function scopeFilter($query, array $filters)
	{
		$query->when($filters['search'] ?? null, function ($query, $search) {
			if ($search) {
				// Convert curly quotes to standard quotes
				$search = Helpers::convert_smart_quotes($search);

				$query->orWhere('id', 'like', '%' . $search . '%')
					->orWhere('title', 'like', '%' . $search . '%');
			}
        })->when($filters['status'] ?? null, function($query, $status) {
			if ($status === 'public') {
				$query->where('published', 1);
			} else if ($status === 'private') {
				$query->where('published', 0);
			} else if ($status === 'deleted') {
				$query->whereNotNull('deleted_at');
			}
		}, function($query) {
			$query->whereNull('deleted_at');
		})->when($filters['stock_status'] ?? null, function($query, $status) {
			if ($status === 'in_stock') {
				$query->where('out_of_stock', 0);
			} elseif ($status === 'out_of_stock') {
				$query->where('out_of_stock', 1);
			}
		})->when($filters['product_type'] ?? null, function($query, $status) {
			if ($status === 'boxes') {
				$query->where('title', 'like', '% Box');
			} else if ($status === 'tins') {
				$query->where('title', 'like', '% Tin');
			}
		});
	}
}