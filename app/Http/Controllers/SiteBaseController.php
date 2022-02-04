<?php namespace App\Http\Controllers;

use App\Models\Occasion as Occasion;
use App\Models\Cookie as Cookie; 
use App\Models\Site;
use TakeoffDesignGroup\CMS\Models\Pages\Page;
use TakeoffDesignGroup\CMS\Models\Widgets\Category;
use TakeoffDesignGroup\CMS\Models\Widgets\Widget;
use TakeoffDesignGroup\CMS\Helpers\Helpers;

class SiteBaseController extends Controller {

	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->site = Site::find(1);
		$this->navigation = Page::navigation($this->site->id, NULL, true)->get();

        // Format redirect URLs.
        foreach ($this->navigation as $page)
        {
        	if ($page->redirect_page_id > 0)
    			$page->redirectUrl = Helpers::getParentFolder($page->redirect_page_id);
    	}		

	    // Share global objects.
		\View::share('nav', $this->navigation);
	}
}