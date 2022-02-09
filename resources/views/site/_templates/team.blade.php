@extends ('site.index')

@section('page_content')
    <section id="row-1" class=" pt-10 pb-10">
        <div class="max-w-screen-xl space-y-12 text-center lg:mx-auto px-4 xl:px-0">
            <div class="space-y-5 sm:mx-auto sm:max-w-xl sm:space-y-4 lg:max-w-5xl">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $name }}</h2>
                <p class="text-xl text-gray-500">Ornare sagittis, suspendisse in hendrerit quis. Sed dui aliquet lectus sit pretium egestas vel mattis neque.</p>
            </div>
            
            @if (count($team) > 0)
                <div class="max-w-4xl mx-auto">
                    <ul role="list" class="grid grid-cols-4 gap-6">
                        @foreach ($team as $advisor)
                        <li class="w-full flex mb-0">
                            <div class="w-full relative bg-white border border-gray-200 rounded-lg p-3 hover:border-gray-300 hover:shadow-md">
                                <div class="absolute top-0 left-0 z-0 w-full h-[62px] bg-blue-500 rounded-t-lg"></div>

                                <div class="h-full flex flex-col relative z-10 space-y-4">
                                    <img class="mx-auto h-32 w-32 rounded-full object-cover" src="{{ $advisor['PhotoURL'] }}" alt="">

                                    <div class="flex-1">
                                        <h3 class="font-medium text-lg leading-6">{{ $advisor['Name'] }}</h3>
                                        <p class="text-[14px] text-gray-500">{{ $advisor['ShortBio'] }}</p>
                                    </div>

                                    <div>
                                        <button class="w-full px-6 py-1 border border-blue-500 rounded-2xl hover:bg-blue-50 hover:ring-1 hover:ring-blue-500 focus:bg-blue-50 focus:ring-1 focus:ring-blue-500 active:bg-blue-100 active:ring-1 active:ring-blue-900" aria-label="">
                                            <span class="font-medium">Bio</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-xl text-gray-500">No members have been added to the {{ $name }} team.</p>
            @endif
        </div>
    </section>
@stop