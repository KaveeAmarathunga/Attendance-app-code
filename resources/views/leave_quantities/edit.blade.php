@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Leave Quantity</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('leave-quantities.update', $leaveQuantity) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="leave_type" class="form-label">Leave Type</label>
            <input type="text" name="leave_type" class="form-control" value="{{ old('leave_type', $leaveQuantity->leave_type) }}" required>
        </div>

        <div class="mb-3">
            <label for="leaves_for_executive" class="form-label">Leaves for Executives</label>
            <input type="number" name="leaves_for_executive" class="form-control" value="{{ $leaveQuantity->leaves_for_executive }}" required>
        </div>

        <div class="mb-3">
            <label for="leaves_for_technicians" class="form-label">Leaves for Technicians</label>
            <input type="number" name="leaves_for_technicians" class="form-control" value="{{ $leaveQuantity->leaves_for_technicians }}" required>
        </div>

        <div class="mb-3">
            <label for="leaves_for_topmanagement" class="form-label">Leaves for Top Management</label>
            <input type="number" name="leaves_for_topmanagement" class="form-control" value="{{ $leaveQuantity->leaves_for_topmanagement }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('leave-quantities.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
