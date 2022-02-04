<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <title>{{ $seo ? $seo->title : $site->title }}</title>
    <meta name="description" content="{{ $seo ? $seo->description : $site->seo_description }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('head')
    @if ($site->meta_tags !== '' && empty($print)){!! $site->meta_tags !!}@endif
</head>
<body>
    <div>
        @yield('banner')
        <div class="page">
            @yield('nav')
        </div>
        
        @yield('content')

        @include('site._partials.footer')
    </div>

    @yield('scripts')
    @if (isset($site) && $site->scripts !== '' && empty($print))
        {!! $site->scripts !!}
    @endif
</body>
</html>