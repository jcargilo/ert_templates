@php
  $path = request()->path();
@endphp

<div class="bg-gray-200 px-6 pt-8 pb-12">
  <div class="pb-6 text-center">
    <h2>Upcoming Events</h2>
  </div>

  @if ($upcomingEvents->count() > 0)
  <div class="mx-auto max-w-7xl">
      <div class="grid gap-6 sm:grid-cols-2 lg:gap-4 lg:grid-cols-3">
        @foreach ($upcomingEvents as $event)
          @include('site._templates.event_details', ['event' => $event])
        @endforeach
      </div>
    </div>

    @if ($path !== 'events')
      <div class="mt-6 flex justify-center">
        <a href="/events" class="button">
          See More Events
        </a>
      </div>
    @endif
  @else
    <p class="rounded-lg bg-white p-12 text-xl text-center text-gray-500 shadow-lg sm:mx-auto sm:max-w-xl">
      There are currently no upcoming events.
    </p>
  @endif
</div>