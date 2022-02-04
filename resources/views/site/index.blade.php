@extends('site._layouts.default')

@section('head')
	@section('page_head')
	@show
@stop

@section('nav')
	@include('site._partials.navigation')
@stop

@section('content')
 	<div id="content">
	 	@section('page_content')
	 	@show
 	</div>
@stop

@section ('scripts')
	@section('page_scripts')
 	@show
@stop