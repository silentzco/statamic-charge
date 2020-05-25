@extends('statamic::layout')

@section('content')
    <h2>Please note that saving the settings may take up to 20 secs. Please wait until you see the "Saved" message.</h2>
    <publish-form
        title="Settings"
        action="{{ cp_route('charge.settings.update') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop