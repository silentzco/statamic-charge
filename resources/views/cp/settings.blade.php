@extends('statamic::layout')

@section('title', 'Configuration')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Configuration</h1>
    </div>
    <div class="p-0 card">
        <form action="/cp/charge/settings" method="POST">
            @csrf
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Plan</th>
                        <th scope="col">Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($settings['subscription']['roles'] as $rolePlan)
                        <tr>
                            <td>
                                <select name="rolePlan[{{ $loop->index }}][plan]">
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" @if ($plan->id == $rolePlan['plan']) selected @endif>{{ $plan->nickname }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="rolePlan[{{ $loop->index }}][role]">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id() }}" @if ($role->id() == $rolePlan['role']) selected @endif>{{ $role->title() }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn-primary">Save</button>
        </form>

    </div>
@endsection