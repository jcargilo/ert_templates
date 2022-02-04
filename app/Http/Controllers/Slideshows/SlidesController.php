<?php

namespace App\Http\Controllers\Slideshows;

use App\Models\Slideshows\Slide;
use TakeoffDesignGroup\CMS\Http\Requests\Slideshow\Slide\StoreSlide;
use TakeoffDesignGroup\CMS\Http\Requests\Slideshow\Slide\UpdateSlide;
use TakeoffDesignGroup\CMS\Models\Slideshows\Slideshow;
use Illuminate\Http\Request;
use Helpers;

class SlidesController extends \TakeoffDesignGroup\CMS\Http\Controllers\Slideshows\SlidesController
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreSlide $request)
    {
        $slideshow = Slideshow::find($request->get('slideshow_id'));
        if (!Helpers::modelExists($slideshow, 'slideshow', $this->site->id))
            return redirect()->route('slideshows.index');

        $slide = new Slide($request->except('action'));
        $slide->author_id = $this->currentUser->id;

        if (!$request->has('overlay'))
            $slide->overlay = '';

        // Get next rank.
        $slide->rank = Slide::getNextRank();
        $slide->save();

        session()->flash('success', sprintf('The slide <em>%s</em> has been successfully published.', 
            $slide->title));

        return redirect()->route('slideshows.slides.show', $slide->slideshow_id);
    }

    public function update(UpdateSlide $request, $id)
    {
        $slideshow = Slideshow::find($request->get('slideshow_id'));
        if (!Helpers::modelExists($slideshow, 'slideshow', $this->site->id))
            return redirect()->route('slideshows.index');

        $data = $request->except('action');
        $slide = Slide::find($id);
        $slide->fill($data);
        $slide->save();

        session()->flash('success', sprintf('The slide <em>%s</em> has been successfully updated.', 
            $slide->title));

        if ($request->has('action') && $request->get('action') === 'save')
            return redirect()->route('slideshows.slides.show', $slide->slideshow_id);
        else
            return redirect()->route('slideshows.slides.edit', $slide->id);
    }
}
