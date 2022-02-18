@extends((isset($subfolder) ? $subfolder : 'site') . '.index')

@section('content')
	@if ($page->slug === 'planning-process')
		<div class="max-w-6xl mx-auto flex justify-end mt-4 px-4">
			<a href="https://ffo.biz-diagnostic.com" class="button" target="_blank">
				Proactive Planning Platform <i class="fas fa-external-link ml-2"></i>
			</a>
		</div>
	@endif

	@if (count($page->sections) > 0)
		@foreach ($page->sections as $key => $section)
			<section id="row-{!! $key !!}" class="{!! $section->class !!}{{ $section->classes ? " {$section->classes}" : '' }}" style="{{ $section->style }}">
				@if ($section->background_image)
					<span aria-hidden="true" class="absolute inset-0 z-10 bg-primary opacity-60 mix-blend-multiply pointer-events-none"></span>
				@endif

				<div class="{{ 
					(!$section->full_width ? 'max-w-screen-xl lg:mx-auto px-4 xl:px-0' : ' w-full') .
					(" flex flex-wrap gap-6 " . $section->vertical_alignment) .
					($section->row_classes ? " {$section->row_classes}" : '')
				}}"{!! $section->pageStyle === '' ? '' : ' style="'.$section->pageStyle.'"' !!}>
					@if ($section->border_color !== '' && $section->border_style !== '' && 
						($section->border_top_width > 0 || $section->border_right_width > 0 || $section->border_bottom_width > 0 || $section->border_left_width > 0))
						<div class="flex-grow flex flex-wrap gap-6 {{ $section->vertical_alignment }}" {!! $section->borderStyle === '' ? '' : ' style="'.$section->borderStyle.'"' !!}>
					@endif

					@for($i = 0; $i < $last_column = (is_array($section->layout) ? count($section->layout) : 1); $i++)
						@php
							$layout_css = '';
							foreach ($section->layout_css as $layout) {
								if (!empty($layout))
									$layout_css .= is_array($layout) ? $layout[$i] ?? $layout[0] : $layout;
							}
							$layout_css = trim($layout_css);
			   			@endphp

						<div class="w-full {{ $layout_css ?: '' }} {{ $section->columns[$i]->classes ? trim($section->columns[$i]->classes) : '' }}"{!! $section->columns[$i]->styles ? " style=\"{$section->columns[$i]->styles}\"" : '' !!}>
							<div class="inner flex-1">
								@foreach($section->columns as $column)
									@if($column->column == $i + 1)
										@if ($column->template_id !== NULL)
											{!! $column->template !!}
										@elseif ($column->slideshow_id !== NULL)
											<div id="slideshow-{!! $column->id !!}" class="bxslider invisible">
												@foreach ($column->slideshow->slides as $slide)
													<div class="slider-item relative">
														@if ($slide->link !== '')
															<a href="{!! $slide->link !!}">
														@endif

															<picture>
																<source media="(min-width:768px)"
																	srcset="{!! $slide->image ? " {$slide->image} 2000w" : '' !!}" />
																<img class="w-full" src="{!! url($slide->image_mobile ?? $slide->image) !!}" alt="{!! $slide->alt_text !!}"{!! $slide->caption && !$slide->overlay != '' ? ' title="'.htmlentities($slide->caption).'"' : '' !!} />
															</picture>

														@if ($slide->link !== '')
															</a>
														@endif

														@if ($slide->overlay && $slide->caption)
															<div class="w-full absolute absolute-vcenter text-center text-white font-bold text-5xl">{!! $slide->caption !!}</div>
														@endif
													</div>
												@endforeach
											</div>
										@else
											{!! $column->content !!}
										@endif
									@endif
								@endforeach
							</div>
						</div>
					@endfor	

					@if ($section->border_color !== '' && $section->border_style !== '' && 
						($section->border_top_width > 0 || $section->border_right_width > 0 || $section->border_bottom_width > 0 || $section->border_left_width > 0))
						</div>
					@endif				
				</div>

				@if (!empty($section->background_image) && $section->background_stretch && $section->scroll_next && $key < 2)
					<a class="arrow"></a>
			    @endif
			</section>
		@endforeach
	@else
		<section class="px-6 py-8">
			<div class="max-w-6xl mx-auto">
				<h2>Coming Soon</h2>
				<p>This page is not quite ready yet.  Please check back soon!</p>
			</div>
		</section>
	@endif
@stop

@section('head')
	@yield('page_head')
@stop

@section('scripts')
	{{-- @if (count($page->sections) > 0)
	<script language="javascript">
		$(function(){
		@foreach ($page->sections as $section)
			@foreach ($section->columns as $key => $column)
				@if($column->slideshow_id != NULL)	
	        	    @if ($column->slideshow->slides->count() > 1)	
						var s = $('#slideshow-{!! $column->id !!}');
	        	    	s.options = {
	        	    		autoPlay: {!! $column->slideshow->auto ? 'true' : 'false'  !!},
		        	    	loop: {!! $column->slideshow->loop ? 'true' : 'false' !!},
		        	    	hideControlOnEnd: true,
		        	    	minSlides: {!! $column->slideshow->min_slides !!},
		        	    	maxSlides: {!! $column->slideshow->max_slides !!},
		        	    	moveSlides: {!! $column->slideshow->move_slides !!},
							@if ($column->slideshow->width > 0)
							slideWidth: {!! $column->slideshow->width !!},
							@endif
							slideMargin: {!! $column->slideshow->margin > 0 ? $column->slideshow->margin : 0 !!},
							pause: {!! $column->slideshow->pause !!},
							speed: {!! $column->slideshow->speed !!},
							captions: {!! $column->slideshow->show_captions ? 'true' : 'false'  !!},
							controls: {!! $column->slideshow->show_controls ? 'true' : 'false' !!},
							pager: {!! $column->slideshow->show_pager ? 'true' : 'false' !!},
	        	    	};
						slideshow.load(s);
						s.removeClass('invisible');
	                @else
					    $('#slideshow-{!! $column->id !!}').removeClass('invisible');
	                @endif
		        @endif
		    @endforeach
	   	@endforeach
	    });
	</script>
	@endif --}}

	@if((!empty($posts) && BLOG_COMMENTS_ENABLED))
	    <div id="fb-root"></div>
	    <script>(function(d, s, id) {
	      var js, fjs = d.getElementsByTagName(s)[0];
	      if (d.getElementById(id)) return;
	      js = d.createElement(s); js.id = id;
	      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=658789207473284";
	      fjs.parentNode.insertBefore(js, fjs);
	    }(document, 'script', 'facebook-jssdk'));
	    </script>
	@endif
@stop