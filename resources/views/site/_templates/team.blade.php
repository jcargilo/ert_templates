@extends ('site.index')

@section('page_content')
    <team name="{{ $name }}" :team="{{ json_encode($team) }}"></team>
@stop