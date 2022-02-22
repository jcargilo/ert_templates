<nav class="flex flex-col justify-between ml-3 md:flex-1 xl:space-y-4">
	<div class="hidden absolute top-0 right-2 md:flex md:justify-end xl:relative xl:right-0">
		<a 
			href="javascript:void"
			class="button button-sm"
			{{-- target="_blank" --}}
		>
			Client Portal <i class="fas fa-external-link ml-2"></i>
		</a>
	</div>

	<ul class="hidden flex-1 md:gap-5 md:flex md:justify-center lg:gap-6 xl:justify-end xl:gap-8">
	    @foreach($nav as $key => $item) 
			{{-- // Ignore items that are not set to be displayed in the menu. --}}
			@if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
				<li id="{{ $item->slug }}" class="{{ $item->subpages->count() > 0 ? ' has-submenu group' : '' }}{{ (isset($page) && ($item->title === $page->title || ($page->parent_category_id > 0 && $item->title === $page->parent->title)) ? ' selected' : '') }}{{ $key == 1 ? ' first' : '' }}">
        			{{-- If the page has subpages, setup submenu(s). --}}
        			@if (count ($item->subpages) == 0) 
        				<a
							class="font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
							href="{{ URL::to('/'.$item->slug) }}"
							{{ $item->redirect_new_window ? 'target="_blank"' : '' }}
						>
							<span>{{ $item->title }}</span>
						</a>
        			@else
						<div class="flex-col relative">
							{{-- Don't allow main nav item to redirect user when a submenu is present. --}}
							<a role="button" class="pr-4 relative font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" href="javascript:void">
								<span>{{ $item->title }}</span>
								<i class="absolute right-0 bottom-0 far fa-angle-down transition-all group-hover:rotate-180"></i>
							</a>
							
							<nav class="opacity-0 invisible group-hover:visible group-hover:opacity-100 {{ $item->slug === 'planning-process' ? 'min-w-[365px]' : 'min-w-[300px]' }} pt-4 absolute right-1/2 left-1/2 transform -translate-x-1/2 z-40">
								<div class="grid grid-rows-auto gap-4 text-center bg-primary p-4">
									@foreach($item->subpages as $second) 
										<a
											class="font-sans tracking-wider text-white decoration-2 hover:underline hover:underline-offset-8 focus:outline-none focus:ring-2 focus:ring-white"
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