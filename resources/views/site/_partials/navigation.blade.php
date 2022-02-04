<nav class="md:flex-1">
	<ul class="hidden justify-center md:flex lg:justify-end gap-8">
	    @foreach($nav as $key => $item) 
			{{-- // Ignore items that are not set to be displayed in the menu. --}}
			@if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
				<li id="{{ $item->slug }}" class="{{ $item->subpages->count() > 0 ? ' has-submenu group' : '' }}{{ (isset($page) && ($item->title === $page->title || ($page->parent_category_id > 0 && $item->title === $page->parent->title)) ? ' selected' : '') }}{{ $key == 1 ? ' first' : '' }}">
        			{{-- If the page has subpages, setup submenu(s). --}}
        			@if (count ($item->subpages) == 0) 
        				<a
							class="font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8"
							href="{{ URL::to('/'.$item->slug) }}"
						>
							<span>{{ $item->title }}</span>
						</a>
        			@else
						<div class="flex-col relative">
							{{-- Don't allow main nav item to redirect user when a submenu is present. --}}
							<a class="relative font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8 pb-2 pr-4" href="javascript:void(0)">
								<span>{{ $item->title }}</span>
								<i class="absolute right-0 bottom-2 far fa-angle-down transition-all group-hover:rotate-180"></i>
							</a>
							<nav class="opacity-0 invisible group-hover:visible group-hover:opacity-100 min-w-[200px] pt-4 absolute right-1/2 left-1/2 transform -translate-x-1/2 z-40">
								<div class="grid grid-rows-auto gap-4 text-center bg-blue-500 p-4">
									@foreach($item->subpages as $second) 
										<a
											class="font-sans tracking-wider text-white decoration-2 hover:underline hover:underline-offset-8"
											href="{{ URL::to('/'.$item->slug.'/'.$second->slug.'/') }}"
										>{{ $second->title }}</a>
									@endforeach
								</div>
							</nav>
						</div>
        			@endif
	            </li>
	        @endif
	    @endforeach
	</ul>
</nav>