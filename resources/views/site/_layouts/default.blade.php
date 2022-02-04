<!doctype html>
<html class="no-js h-screen" lang="">
<head>
    <meta charset="utf-8">
    <title>{{ $seo ? $seo->title : $site->title }}</title>
    <meta name="description" content="{{ $seo ? $seo->description : $site->seo_description }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @isset($site->favicon)<link rel="icon" type="image/png" href="{{ $site->favicon }}">@endisset
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    @yield('head')
    @if ($site->meta_tags !== '' && empty($print)){!! $site->meta_tags !!}@endif
</head>
<body class="relative h-full p-0 bg-gray-100">
    <div class="flex flex-col h-full">
        <div class="items-center px-6 py-4 bg-white lg:flex">
            <div class="flex justify-between items-center md:block">
                <a href="/">
                    <img src="./images/static/logo.png" class="m-auto min-w-[250px] max-w-[300px] pb-0 md:pb-4 lg:pb-0">
                </a>

                <svg class="h-6 w-6 text-[#004261] hover:text-[#195f80] md:hidden" aria-hidden="true" focusable="false"
                    data-prefix="far" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14 fa-3x">
                    <path fill="currentColor"
                        d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z"
                        class=""></path>
                </svg>
            </div>

            @yield('nav')
        </div>

        @yield('banner')
        
        @yield('content')

        @include('site._partials.footer')
    </div>

    <script src="{{ mix('/js/app.js') }}" type="text/javascript"></script>
    @yield('scripts')
    @if (isset($site) && $site->scripts !== '' && empty($print))
        {!! $site->scripts !!}
    @endif
</body>
</html>