@extends('statamic::layout')

@section('title', 'Customers')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>Customers</h1>
    </div>
    <div class="p-0 card">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Stripe ID</th>
                    <th scope="col" class="actions-column"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td><a href="https://dashboard.stripe.com/customers/{{ $customer->stripe_id }}">Go to Stripe Customer</a></td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection