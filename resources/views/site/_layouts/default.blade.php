<!doctype html>
<html class="no-js h-screen" lang="">
<head>
    <meta charset="utf-8">
    <title>{{ isset($seo) ? $seo->title : $site->title }}</title>
    <meta name="description" content="{{ isset($seo) ? $seo->description : $site->seo_description }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @isset($site->favicon)<link rel="icon" type="image/png" href="{{ $site->favicon }}">@endisset
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-rqn26AG5Pj86AF4SO72RK5fyefcQ/x32DNQfChxWvbXIyXFePlEktwD18fEz+kQU" crossorigin="anonymous">
    @yield('head')
    @if ($site->meta_tags !== '' && empty($print)){!! $site->meta_tags !!}@endif
    <style>
        :root{
          --primary-color: {{ Arr::get($site->attributes, 'theme.primary_color', '#555555') }};
          --secondary-color: {{ Arr::get($site->attributes, 'theme.secondary_color', '#888888') }};
          --link-color: {{ Arr::get($site->attributes, 'theme.link_color', Arr::get($site->attributes, 'theme.primary_color', '#555555') ) }};
          --link-hover-color: {{ Arr::get($site->attributes, 'theme.link_hover_color', Arr::get($site->attributes, 'theme.secondary_color', '#888888')) }};
          --link-active-color: {{ Arr::get($site->attributes, 'theme.link_active_color', Arr::get($site->attributes, 'theme.primary_color', '#555555')) }};
          --button-bg-color: {{ Arr::get($site->attributes, 'theme.button_bg_color', Arr::get($site->attributes, 'theme.primary_color', '#555555') ) }};
          --button-text-color: {{ Arr::get($site->attributes, 'theme.button_text_color', '#ffffff') }};
          --button-hover-bg-color: {{ Arr::get($site->attributes, 'theme.button_hover_bg_color', Arr::get($site->attributes, 'theme.secondary_color', '#888888') ) }};
          --button-hover-text-color: {{ Arr::get($site->attributes, 'theme.button_hover_text_color', '#ffffff') }};
          --button-active-bg-color: {{ Arr::get($site->attributes, 'theme.button_active_bg_color', Arr::get($site->attributes, 'theme.secondary_color', '#888888') ) }};
          --button-active-text-color: {{ Arr::get($site->attributes, 'theme.button_active_text_color', '#ffffff') }};
          --button-ring-color: {{ Arr::get($site->attributes, 'theme.button_focus_ring_color', Arr::get($site->attributes, 'theme.secondary_color', '#888888') ) }};
        }
    </style>
</head>
<body class="relative h-full p-0 bg-gray-100 selection:bg-primary selection:text-white">
    <div id="mobile-menu" class="transition duration-100 -translate-x-full p-4 pt-8 absolute top-0 left-0 bg-gray-100 w-full z-50">
        <div class="text-right">
            <button
                id="mobile-close"
                type="button"
                class="inline-flex items-center justify-center py-1 px-2 rounded-md text-2xl text-secondary hover:text-primary focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary"
                aria-controls="mobile-menu" aria-expanded="true">
                <span class="sr-only">Close main menu</span>
                <svg class="w-8 h-8" aria-hidden="true" focusable="false" data-prefix="far" data-icon="times" role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"
                    class="svg-inline--fa fa-times fa-w-10 fa-3x">
                    <path fill="currentColor"
                        d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z"
                        class=""></path>
                </svg>
            </button>
        </div>
        
        <div class="w-full h-screen pt-6">
            <div class="grid grid-rows-auto gap-8 text-2xl text-center">
                @foreach($nav as $key => $item)
                    @if ($item->display_in_menu || (isset($mobile) && $item->display_in_footer))
                        <div>
                            @if (count ($item->subpages) == 0) 
                                <a
                                    class="font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8"
                                    href="{{ URL::to('/'.$item->slug) }}"
                                >
                                    <span>{{ $item->title }}</span>
                                </a>
                            @else
                                <div class="flex-col relative">
                                    <a data-id="{{ $item->id }}" class="relative font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8 pr-6" href="javascript:void(0)">
                                        <span>{{ $item->title }}</span>
                                        <i class="absolute right-0 bottom-0 far fa-angle-down transition-all"></i>
                                    </a>
                                    <nav data-submenu="{{ $item->id }}" class="bg-gray-200 grid-rows-auto gap-4 mt-4 p-4 hidden">
                                        @foreach($item->subpages as $second) 
                                            <a
                                                class="font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8"
                                                href="{{ URL::to('/'.$item->slug.'/'.$second->slug.'/') }}"
                                            >{{ $second->title }}</a>
                                        @endforeach
                                    </nav>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach

                <div class="md:hidden">
                    <a
                        class="font-sans tracking-wider text-primary hover:text-primary decoration-2 hover:underline hover:underline-offset-8"
                        href="{{ Arr::get($site->attributes, 'client_portal_link', '#') }}"
                        target="_blank"
                    >
                        <span>Client Portal</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col">
        <div class="px-6 py-4 bg-white">
            <div class="relative max-w-6xl mx-auto md:space-y-4 xl:space-y-0 xl:flex">
                <div class="flex justify-between items-center md:justify-center">
                    @if ($site->logo)
                        <a href="/" class="flex justify-center min-w-[250px] pb-0">
                            <img src="{{ $site->logo }}" class="max-w-[275px] max-h-[75px] xl:max-w-[330px]" alt="{{ $site->title }}">
                        </a>
                    @else
                        <a href="/" class="bg-gray-100 inline-flex items-center justify-center p-6 rounded-lg max-w-[275px] xl:max-w-[400px]">
                            <span class="font-bold text-2xl text-primary text-center">{{ $site->title }}</span>
                        </a>
                    @endif

                    <button id="mobile-open" class="ml-4 md:hidden" type="button" aria-expanded="false">
                        <svg class="h-8 w-8 text-primary hover:text-secondary" aria-hidden="true" focusable="false"
                            data-prefix="far" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14 fa-3x">
                            <path fill="currentColor"
                                d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z"
                                class=""></path>
                        </svg>
                    </button>
                </div>

                @yield('nav')
            </div>
        </div>

        @yield('banner')
        
        <div id="app">
            @yield('content')
        </div>

        @include('site._partials.footer')
    </div>

    <script src="{{ mix('/js/app.js') }}" type="text/javascript"></script>
    @yield('scripts')
    @if (isset($site) && $site->scripts !== '' && empty($print))
        {!! $site->scripts !!}
    @endif
</body>
</html>