@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leave Quantities</h1>

    <a href="{{ route('leave-quantities.create') }}" class="btn btn-primary mb-3">Add New Leave Quantity</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Leave Type</th>
                <th>Executives</th>
                <th>Technicians</th>
                <th>Top Management</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($leaveQuantities as $quantity)
            <tr>
                <td>{{ $quantity->id }}</td>
                <td>{{ $quantity->leave_type }}</td>
                <td>{{ $quantity->leaves_for_executive }}</td>
                <td>{{ $quantity->leaves_for_technicians }}</td>
                <td>{{ $quantity->leaves_for_topmanagement }}</td>
                <td>
                    <a href="{{ route('leave-quantities.show', $quantity) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('leave-quantities.edit', $quantity) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('leave-quantities.destroy', $quantity) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No leave quantities found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
