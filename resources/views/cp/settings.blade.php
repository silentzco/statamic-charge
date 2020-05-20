@extends('statamic::layout')

@section('title', 'Configuration')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Configuration</h1>
    </div>
    <div class="p-0 card">
        <form action="/cp/charge/settings" method="POST">
            @csrf
            <role-plans
                :plans="{{ json_encode($plans) }}"
                :roles="{{ json_encode($roles) }}"
                :role-plans="{{ json_encode($settings['subscription']['roles']) }}"
            >

            </role-plans>
            <button type="submit" class="btn-primary">Save</button>
        </form>

    </div>
@endsection