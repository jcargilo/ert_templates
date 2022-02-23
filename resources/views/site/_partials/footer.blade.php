<footer class="px-4">
    <div class="max-w-6xl mx-auto my-4">
        <div class="mb-6 lg:flex lg:items-center">
            <ul class="flex flex-col text-center gap-2 mb-6 lg:mb-0 lg:text-left lg:gap-10 lg:flex-row">
                @foreach($nav as $key => $item) 
                    @if ($item->display_in_footer)
                        <li id="{{ $item->slug }}">
                            <a class="font-sans tracking-wider text-xl text-primary decoration-2 lg:text-base hover:underline hover:underline-offset-8"
                                href="{{ URL::to('/'.$item->slug) }}">
                                <span>{{ $item->title }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            @if (!empty($social))
                <ul class="flex flex-1 justify-center space-x-4 text-lg lg:justify-end">
                    @foreach ($social as $link)
                    <li>
                        <a href="{{ $link->url }}" class="text-primary hover:text-secondary" target="_blank">
                            <i class="fab fa-{{ $link->class }} text-2xl"></i>
                            <span class="sr-only">Follow us on {{ $link->title }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="font-sans text-xs text-gray-600">
            © {{ date('Y') }} {{ $site->title }}
        </div>
    </div>
</div>