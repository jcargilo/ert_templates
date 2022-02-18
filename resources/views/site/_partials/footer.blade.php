<footer class="px-6 py-4">
    <div class="max-w-6xl mx-auto">
        <nav class="md:flex">
            <ul class="md:flex gap-10">
                @foreach($nav as $key => $item) 
                    @if ($item->display_in_footer)
                        <li id="{{ $item->slug }}">
                            <a class="font-sans tracking-wider text-primary decoration-2 hover:underline hover:underline-offset-8"
                                href="{{ URL::to('/'.$item->slug) }}">
                                <span>{{ $item->title }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            @if (!empty($social))
            <ul class="flex flex-1 justify-center space-x-4 text-lg md:justify-end">
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
        </nav>

        <div class="py-4 font-sans text-xs text-gray-600">
            © {{ date('Y') }} {{ $site->title }}
        </div>
    </div>
</div>