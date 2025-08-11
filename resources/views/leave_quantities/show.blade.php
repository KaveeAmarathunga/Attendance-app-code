@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leave Quantity Details</h1>

    <div class="mb-3">
        <strong>Leave Type:</strong> {{ $leaveQuantity->leave_type }}
    </div>

    <div class="mb-3">
        <strong>Leaves for Executives:</strong> {{ $leaveQuantity->leaves_for_executive }}
    </div>

    <div class="mb-3">
        <strong>Leaves for Technicians:</strong> {{ $leaveQuantity->leaves_for_technicians }}
    </div>

    <div class="mb-3">
        <strong>Leaves for Top Management:</strong> {{ $leaveQuantity->leaves_for_topmanagement }}
    </div>

    <a href="{{ route('leave-quantities.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('leave-quantities.edit', $leaveQuantity) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
