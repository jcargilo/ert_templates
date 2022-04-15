@if ($pastEvents->count() > 0)
  <div class="bg-gray-200 px-6 pt-8 pb-12">
    <div class="pb-6 text-center">
      <h2>Past Events</h2>
    </div>

    <div class="mx-auto max-w-7xl">
        <div class="grid gap-6 sm:grid-cols-2 lg:gap-4 lg:grid-cols-3">
          @foreach ($pastEvents as $event)
            @include('site._templates.event_details', [
              'event' => $event,
              'past' => true,
            ])
          @endforeach
        </div>
      </div>
  </div>
@endif