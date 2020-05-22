@extends('statamic::layout')

@section('title', 'Configuration')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Configuration</h1>
    </div>
    <div class="p-0 card">
        <form action="/cp/charge/settings" method="POST">
            @csrf
            <h2>Subscriptions</h2>
            <role-plans
                :plans="{{ json_encode($plans) }}"
                :roles="{{ json_encode($roles) }}"
                :role-plans="{{ json_encode(\Illuminate\Support\Arr::get($settings, 'subscription.roles', [])) }}"
            >

            </role-plans>

            <h2>Email</h2>
            <label for="from">From:</label>
            <input type="text" id="from" name="email[from]" value="{{ $settings['email']['from'] }}">
            <h3>One Time</h3>
            <label for="one-time-template">Template:</label>
            <select id="one-time-template" name="email[one_time][payment_template]">
                @foreach ($templates as $template)
                    <option value="{{ $template }}" @if ($settings['email']['one_time']['payment_template'] == $template) selected @endif>{{ $template }}</option>
                @endforeach
            </select>
            <label for="one-time-subject">Subject:</label>
            <input type="text" id="one-time-subject" name="email[one_time][payment_subject]" value="{{ $settings['email']['one_time']['payment_subject'] }}">
            <button type="submit" class="btn-primary">Save</button>
        </form>

    </div>
@endsection