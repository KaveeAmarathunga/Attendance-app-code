@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Leave Requests</h2>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary mb-3">Create Leave Request</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>EPF Number</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Status</th>
                <th>Paid</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->id }}</td>
                    <td>{{ $leave->epf_number }}</td>
                    <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                    <td>{{ $leave->from_date }}</td>
                    <td>{{ $leave->to_date }}</td>
                    <td>{{ $leave->status }}</td>
                    <td>{{ $leave->paid ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('leaves.edit', $leave->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection