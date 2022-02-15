<nav class="flex flex-col justify-between md:flex-1">
	<div class="hidden absolute top-2 right-0 md:block">
		<a href="javascript:void" class="text-sm">Client Portal</a>
	</div>

	<ul class="hidden justify-center gap-6 md:flex lg:mt-8 lg:justify-end xl:gap-8 xl:mt-10">
	    @foreach($nav as $key => $item) 
			{{-- // Ignore items that are not set to be displayed in the menu. --}}
			@if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
				<li id="{{ $item->slug }}" class="{{ $item->subpages->count() > 0 ? ' has-submenu group' : '' }}{{ (isset($page) && ($item->title === $page->title || ($page->parent_category_id > 0 && $item->title === $page->parent->title)) ? ' selected' : '') }}{{ $key == 1 ? ' first' : '' }}">
        			{{-- If the page has subpages, setup submenu(s). --}}
        			@if (count ($item->subpages) == 0) 
        				<a
							class="font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8"
							href="{{ URL::to('/'.$item->slug) }}"
							{{ $item->redirect_new_window ? 'target="_blank"' : '' }}
						>
							<span>{{ $item->title }}</span>
						</a>
        			@else
						<div class="flex-col relative">
							{{-- Don't allow main nav item to redirect user when a submenu is present. --}}
							<a class="relative font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8 pb-2 pr-4" onclick="javascript:void">
								<span>{{ $item->title }}</span>
								<i class="absolute right-0 bottom-2 far fa-angle-down transition-all group-hover:rotate-180"></i>
							</a>
							<nav class="opacity-0 invisible group-hover:visible group-hover:opacity-100 min-w-[300px] pt-4 absolute right-1/2 left-1/2 transform -translate-x-1/2 z-40">
								<div class="grid grid-rows-auto gap-4 text-center bg-blue-500 p-4">
									@foreach($item->subpages as $second) 
										<a
											class="font-sans tracking-wider text-white decoration-2 hover:underline hover:underline-offset-8"
											href="{{ URL::to('/'.$item->slug.'/'.$second->slug.'/') }}"
											{{ $second->redirect_new_window ? 'target="_blank"' : '' }}
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