@extends('statamic::layout')

@section('content')
    <publish-form
        title="Settings"
        action="{{ cp_route('charge.settings.update') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop