@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pending Admin Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pendingAdmins->isEmpty())
        <p>No pending admin requests.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Requested At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingAdmins as $uid => $admin)
                    <tr>
                        <td>{{ $admin['name'] ?? 'N/A' }}</td>
                        <td>{{ $admin['email'] ?? 'N/A' }}</td>
                        <td>{{ $admin['created_at'] ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.approve', $uid) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>

                            <form action="{{ route('admin.reject', $uid) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
