@extends ('site.index')

@php
    $gap = "lg:gap-x-20";
    $size = "h-40 w-40 xl:w-56 xl:h-56";
    if ($name === 'Virtual Family Office') {
        $grid = "lg:grid-cols-6";
        $gap = "lg:gap-x-16";
        $size = "h-40 w-40";
    } else {
        switch (count($team)) {
            case 1:
                $grid = "lg:grid-cols-1";
                break;
            case 2:
                $grid = "lg:grid-cols-2";
                break;
            case 3:
            case 5:
            case 6:
                $grid = "lg:grid-cols-3";
                break;
            default:
                $grid = "lg:grid-cols-4";
        }
    }
@endphp

@section('page_content')
    <section id="row-1" class="content-block relative pt-10 pb-10">
        <div class="max-w-screen-xl space-y-12 text-center lg:mx-auto px-4 xl:px-0">
            <div class="space-y-5 sm:mx-auto sm:max-w-xl sm:space-y-4 lg:max-w-5xl">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $name }}</h2>
                <p class="text-xl text-gray-500">Ornare sagittis, suspendisse in hendrerit quis. Sed dui aliquet lectus sit pretium egestas vel mattis neque.</p>
            </div>
            
            @if (count($team) > 0)
                <ul role="list" class="space-y-16 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-12 sm:space-y-0 {{ $grid }} {{ $gap }}">
                    @foreach ($team as $advisor)
                    <li>
                        <div class="space-y-4">
                            <img class="mx-auto rounded-full {{ $size }} object-cover" src="{{ $advisor['PhotoURL'] }}" alt="">

                            <div class="space-y-2">
                                <div class="text-lg leading-6 font-medium space-y-1">
                                    <h3>{{ $advisor['Name'] }}</h3>
                                    <p class="text-blue-500">{{ $advisor['ShortBio'] }}</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p class="text-xl text-gray-500">Ornare sagittis, suspendisse in hendrerit quis. Sed dui aliquet lectus sit pretium egestas vel mattis neque.</p>
            @endif
        </div>
    </section>
@stop