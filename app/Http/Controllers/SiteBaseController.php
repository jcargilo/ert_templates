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
		view()->share('nav', $this->navigation);
		view()->share('social', $this->getSocialLinks() ?? []);
	}

	protected function getSocialLinks()
	{
		if ($this->site->facebook !== '')
			$links[] = (object)array(
				'title'	=> 'Facebook',
				'class' => 'facebook',
				'url' 	=> 'http://www.facebook.com/'.$this->site->facebook,
			);

		if ($this->site->linkedin !== '')
			$links[] = (object)array(
				'title'	=> 'Linked In',
				'class' => 'linkedin',
				'url' 	=> 'http://www.linkedin.com/'.$this->site->linkedin,
			);

		if ($this->site->twitter !== '')
			$links[] = (object)array(
				'title'	=> 'Twitter',
				'class' => 'twitter',
				'url' 	=> 'http://twitter.com/'.$this->site->twitter,
			);

		if ($this->site->googleplus !== '')
			$links[] = (object)array(
				'title'	=> 'Google+',
				'class' => 'gp',
				'url' 	=> 'http://plus.google.com/'.$this->site->googleplus,
			);

		if ($this->site->instagram !== '')
			$links[] = (object)array(
				'title'	=> 'Instagram',
				'class' => 'inst',
				'url' 	=> 'http://www.instagram.com/'.$this->site->instagram,
			);

		if ($this->site->youtube !== '')
			$links[] = (object)array(
				'title'	=> 'You Tube',
				'class' => 'youtube',
				'url' 	=> 'http://www.youtube.com/'.$this->site->youtube,
			);

		if ($this->site->flickr !== '')
			$links[] = (object)array(
				'title'	=> 'Flickr',
				'class' => 'flickr',
				'url' 	=> 'http://www.flickr.com/'.$this->site->flickr,
			);

		if ($this->site->pinterest !== '')
			$links[] = (object)array(
				'title'	=> 'Pinterest',
				'class' => 'pint',
				'url' 	=> 'http://www.pinterest.com/'.$this->site->pinterest,
			);

		return !empty($links) ? $links : null;
	}
}