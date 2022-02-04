<nav id="nav">
    <ul class="nav-primary">
	    @foreach($nav as $key => $item) 
			{{-- // Ignore items that are not set to be displayed in the menu. --}}
			@if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
				<li id="{{ $item->slug }}" class="{{ $item->subpages->count() > 0 ? 'has-submenu' : '' }}{{ (isset($page) && ($item->title === $page->title || ($page->parent_category_id > 0 && $item->title === $page->parent->title)) ? ' selected' : '') }}{{ $key == 1 ? ' first' : '' }}">
        			{{-- Show Occasions submenu --}}
        			@if ($item->slug === 'occasions' || $item->slug === 'order')
        				<a href="{{ URL::to('/'.$item->slug) }}">{{ ucFirst($item->title) }}</a>
        				<i class="fa collapsed fa-lg visible-xs" data-toggle="collapse" href="#occasions-menu" aria-expanded="false"></i>
        				<ul id="occasions-menu" class="collapse submenu">
        					@foreach ($occasions as $key => $category)
        						@if ($category->title !== 'Cookies')
									@if (!in_array($category->slug, ['gluten-free-cookies']) || !$glutenFreeDisabled)
										<li><a href="{{ URL::to('/occasions/'.$category->slug.'/') }}">{{ $category->title }}</a></li>
									@endif
									@if(!$glutenFreeDisabled && $category->rank !== 100 && (isset($occasions[$key+1]) && $occasions[$key+1]->rank === 100))
                                    	<li class="no-hover"><hr></li>
                                	@endif
                                @endif
                            @endforeach
                        </ul>

                    {{-- If the page has subpages, setup submenu(s). --}}
        			@elseif ( count ($item->subpages) == 0) 
        				<a href="{{ URL::to('/'.$item->slug) }}">{{ ucFirst($item->title) }}</a>
        			@else
        				{{-- Don't allow main nav item to redirect user when a submenu is present. --}}
        				<a href="javascript:void(0)">{{ ucFirst($item->title) }}</a>
        				<ul>

        				{{-- Check to see if the parent is to be included in the subnav. --}}
        				@if ($item->include_in_submenu)
        					<li><a href="{{ URL::to('/'.$item->slug.'/') }}">{{ ($item->submenu_slug_text === '' ? $item->title : $item->submenu_slug_text) }}</a>
        				@endif

        				@foreach($item->subpages as $second) 
        					<li><a href="{{ URL::to('/'.$item->slug.'/'.$second->slug.'/') }}">{{ $second->title }}</a>
        					@if ( !empty ($second->subpages) ) 
        					
        						<ul>
        						@foreach($second->subpages as $third) 
        							<li><a href="{{ URL::to('/'.$item->slug.'/'.$second->slug.'/'.$third->slug.'/') }}">{{ $third->title }}</a></li>
        						@endforeach
        						</ul></li>
        					@else
        						</li>
        					@endif
        				@endforeach
        				</ul>
        			@endif
	            </li>
	        @endif
	    @endforeach
	</ul>
</nav>