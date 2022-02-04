		{{-- <li class="px-4">
			<div class="flex-col">
				<a class="font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8"
					href="#">
					<div class="relative">
						<span class="mr-2">VFO Services</span>
						<i class="far fa-angle-down"></i>
					</div>
				</a>
				<!--
					Sub nav when open.
					Sub nav open:"block", sub nav closed: "hidden"
				-->
				<div
					class="min-w-[200px] my-2 p-2 bg-blue-500 absolute right-1/2 left-1/2 transform -translate-x-1/2 text-white z-40">
					<div class="grid grid-rows-auto gap-4 p-4">
						<a class="font-sans tracking-wider hover:text-white decoration-2 hover:underline hover:underline-offset-8"
							href="#">
							<span>Tax Planning</span></a>
						<a class="font-sans tracking-wider hover:text-white decoration-2 hover:underline hover:underline-offset-8"
							href="#">
							<span>Wealth Management</span></a>
						<a class="font-sans tracking-wider hover:text-white decoration-2 hover:underline hover:underline-offset-8"
							href="#">
							<span>Legal</span></a>
						<a class="font-sans tracking-wider hover:text-white decoration-2 hover:underline hover:underline-offset-8"
							href="#">
							<span>Risk Mitigation</span></a>
						<a class="font-sans tracking-wider hover:text-white decoration-2 hover:underline hover:underline-offset-8"
							href="#">
							<span>Business Advisory</span></a>
					</div>
				</div>
				<!--/Sub nav-->
			</div>
		</li> --}}

<nav class="md:flex-1">
	<ul class="hidden justify-center md:flex lg:justify-end">
	    @foreach($nav as $key => $item) 
			{{-- // Ignore items that are not set to be displayed in the menu. --}}
			@if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
				<li id="{{ $item->slug }}" class="px-4{{ $item->subpages->count() > 0 ? ' has-submenu group' : '' }}{{ (isset($page) && ($item->title === $page->title || ($page->parent_category_id > 0 && $item->title === $page->parent->title)) ? ' selected' : '') }}{{ $key == 1 ? ' first' : '' }}">
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
							<a class="relative font-sans tracking-wider text-blue-500 hover:text-blue-500 decoration-2 hover:underline hover:underline-offset-8 pb-2" href="javascript:void(0)">
								<span class="mr-2">{{ $item->title }}</span>
								<i class="far fa-angle-down"></i>
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