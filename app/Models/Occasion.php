<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB, StdClass, Str, Carbon\Carbon;

class Occasion extends \Eloquent
{
	use SoftDeletes;

	protected $table = 'occasions';
	protected $guarded = [];
	protected $dates = ['start_date', 'end_date'];
	protected $casts = [
		'featured' => 'boolean',
		'seasonal' => 'boolean',
		'limited' => 'boolean'
	];

	public function getSocialImageAttribute($value)
	{
		return \URL::asset(
			$value ?? (
				$this->products->count() > 0 
					? $this->products->first()->image 
					: ''
			)
		);
	}

	public function setStartDateAttribute($date)
	{
		$this->attributes['start_date'] = $date ? Carbon::parse($date) : NULL;
	}

	public function setEndDateAttribute($date)
	{
		$this->attributes['end_date'] = $date ? Carbon::parse($date) : NULL;
	}

	// Properties
	public function getCookiesAttribute($value)
	{
		return json_decode($this->assortment, true);
	}

	// Relationships
	public function setAssortment($cookies)
	{
		if ($this->seasonal || $this->limited)
		{
			// retrieve cookie selection
			$assortment = [];
			$count = 0;
			foreach ($cookies as $key => $value)
			{
				if ($value > 0)
				{
					$count += $value;
					$assortment[$key] = $value;
				}
			}

			// If the cookie selection is fewer than or exceeds 1 dozen cookies, throw validation error.
			if ($count != 12)
			{
				$error = sprintf('In order to set this Occasion to Seasonal, you must specify <b>%s %s</b> cookies.',
					($count < 12 ? 12 - $count : $count - 12),
					($count < 12 ? 'more' : 'less'));
				return $error;
			}

			$this->assortment = json_encode($assortment);
		}
	}

	public function products($published = NULL)
	{
		$products = $this->belongsToMany('App\Models\Product', 'products_in_occasions')
						->withPivot('rank')
						->orderBy('products_in_occasions.rank');

		if ($published !== NULL)
			$products->where('published','=',$published);

		return $products;
	}

	// Scopes
	public function scopePublic($query, $published = true)
	{
		if ($published) {
			$query->where('published','=','1');
		}

		return $query
			->where(function($query) {
				$query->whereNull('start_date')
					->orWhere('start_date', '<', Carbon::now('America/New_York'));
			})
			->where(function($query) {
				$query->whereNull('end_date')
					->orWhere('end_date', '>', Carbon::now('America/New_York'));
			});
	}

	public function scopeFindBySlug($query, $slug)
	{
		$query->where('slug', $slug);
	}

	public function isVisible()
	{
		return $this->published &&
			($this->start_date == NULL || $this->start_date < Carbon::now('America/New_York')) &&
			($this->end_date == NULL || $this->end_date > Carbon::now('America/New_York'));
	}

	/* ADDITIONAL DB Methods */
	public static function clearFeaturedColumn($featured)
	{
		return DB::table('occasions')
			->where('featured', $featured)
			->update(array('featured' => 0));
	}

	/* ADDITIONAL DB Methods */
	public static function getNextRank()
	{
		// Get top record.
		$record = DB::table('occasions')
					->orderBy('rank', 'desc')
					->first();

		// Make sure top record exists.  If top record doesn't exist, set rank to first position.
		if ($record)
			return $record->rank + 1;
		else
			return 1;
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
		$limit = $data['limit'] ?? 25;
		$field = $data['field'] ?? '';
		$direction = $data['direction'] ?? '';

		$results = new StdClass;
		$results->page = $page;
		$results->limit = $limit;
		$results->totalItems = 0;
		$results->items = [];

		if ($field === '' || $field === NULL)
			$field = 'occasions.title';
		else
			$field = 'occasions.'.$field;

		if ($direction === '' || $direction === NULL)
			$direction = 'asc';

		// Setup default queries.
		$query = Occasion::withTrashed()->with('products')
			->filter($data);

		// Add sorting
		$query->orderByRaw("if({$field} = '' or {$field} is null,1,0),{$field} {$direction}");

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
				$search = \Helpers::convert_smart_quotes($search);

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
		});
	}
}