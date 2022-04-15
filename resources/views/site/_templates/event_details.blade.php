@isset($event)
  @php
    $startDateFormat = $event->startDate->year != \Carbon\Carbon::now()->year ? 'l, F j, Y' : 'l, F jS';
    $endDateFormat = $event->endDate
      ? ($event->endDate->year != \Carbon\Carbon::now()->year ? 'l, F j, Y' : 'l, F jS')
      : '';
  @endphp

  <div class="flex-1 flex flex-col overflow-hidden rounded-lg shadow-lg max-w-xl">
    @if (!($past ?? false))
      <div class="flex-shrink-0">
        @if (isset($event->image))
          <a class="block hover:opacity-75" href="{{ $event->url }}" target="_blank">
            <img class="w-full object-cover h-[215px]" src="{{ $event->image }}" alt="" />
          </a>
        @else
          <div class="flex items-center justify-center bg-gray-100 px-6 h-[215px]">
            <img class="w-full" src="/images/static/logo.png" alt="" />
          </div>
        @endif
      </div>
    @endif

    <div class="flex flex-1 flex-col border-t bg-white p-6">
      <div class="flex-1 text-center">
        <a
          href="{{ $event->url }}"
          class="text-lg underline-offset-4 sm:text-xl hover:text-link-hover active:text-link-active"
          target="_blank"
        >
          {{ $event->title }}
        </a>
        <div class="text-sm font-medium text-gray-600 md:text-base">
          @if ($event->endDate)
            @if ($event->startDate->copy()->startOfDay()->eq($event->endDate->copy()->startofDay()))
              <span>
                {{ $event->startDate->format($startDateFormat) }}
                <time datetime="{{ $event->startDate->toDateTimeString() }}">{{ $event->startDate->format('g:ia') }}</time> -
                <time datetime="{{ $event->endDate->toDateTimeString() }}">{{ $event->endDate->format('g:ia T') }}</time>
              </span>
            @else
            <div class="flex flex-col">
              <time datetime="{{ $event->startDate->toDateTimeString() }}">{{ $event->startDate->format($startDateFormat) }} at {{ $event->startDate->format('g:ia') }} to</time>
              <time datetime="{{ $event->endDate->toDateTimeString() }}">{{ $event->endDate->format($endDateFormat) }} at {{ $event->endDate->format('g:ia T') }}</time>
            </div>
            @endif
          @else
            <time datetime="{{ $event->startDate->toDateTimeString() }}">
              {{ $event->startDate->format($startDateFormat) }} | {{ $event->startDate->format('g:ia T') }}
            </time>
          @endif
          </div>
      </div>
    </div>
  </div>
@endisset