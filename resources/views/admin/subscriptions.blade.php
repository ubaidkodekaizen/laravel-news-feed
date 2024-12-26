@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Subscriptions</h4>
                        </div>
                        <div class="card-body">
                            <table id="usersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subscription ID</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>Renewal Date</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $subscription)
                                        <tr>
                                            <td>{{ $subscription->id }}</td>
                                            <td>{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}
                                            </td>
                                            <td>{{ $subscription->user->email }}</td>
                                            <td>{{ $subscription->user->phone }}</td>
                                            <td>{{ $subscription->transaction_id }}</td>
                                            <td>{{ $subscription->subscription_type }}</td>
                                            <td>${{ $subscription->subscription_amount }}</td>
                                            <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('m/d/Y') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($subscription->renewal_date)->format('m/d/Y') }}
                                            </td>
                                            <td>{{ $subscription->status }}</td>

                                            {{-- <td>
                                        <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">View</a> 
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('admin.company.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit Company</a>
                                        <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>

                                    </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>No Users</td>

                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
