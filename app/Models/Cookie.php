<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB, StdClass, Helpers;

class Cookie extends \Eloquent
{
	use SoftDeletes;

	protected $table = 'cookies';
	protected $guarded = ['id'];
	public $casts = [
		'featured' => 'int',
	];

	public function getSlugAttribute($value)
	{
		return \Str::slug($this->title);
	}

	// Scopes
	public function scopeActive($query)
	{
		return $query->where('published', '=', 1)
			->orderBy('title');
	}

	/* ADDITIONAL DB Methods */

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

		$results = new StdClass;
		$results->page = $page;
		$results->limit = $limit;
		$results->totalItems = 0;
		$results->items = array();

		// Setup default queries.
		$query = Cookie::withTrashed()
			->filter($data)
			->orderBy('rank', 'asc');

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

				$query->where(function ($query) use ($search) {
					$query->orWhere('id', 'like', '%' . $search . '%')
						->orWhere('title', 'like', '%' . $search . '%')
						->orWhere('sku', 'like', '%' . $search . '%');
				});
			}
		})->when($filters['status'] ?? null, function($query, $status) {
			if ($status === 'featured') {
				$query->where('featured', '>', 1);
			} else if ($status === 'public') {
				$query->where('published', 1);
			} else if ($status === 'private') {
				$query->where('published', 0);
			} 
			
			if ($status === 'deleted') {
				$query->whereNotNull('deleted_at');
			} else {
				$query->whereNull('deleted_at');	
			}
		});
	}

	public static function getNextRank()
	{
		// Get top record.
		$record = DB::table('cookies')
			->orderBy('rank', 'desc')
			->first();

		// Make sure top record exists.  If top record doesn't exist, set rank to first position.
		if ($record)
			return $record->rank + 1;
		else
			return 1;
	}

	public static function clearFeaturedColumn($featured)
	{
		return DB::table('cookies')
			->where('featured', $featured)
			->update(['featured' => 0]);
	}
}