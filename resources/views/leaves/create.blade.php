@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Leave Request</h2>
    <form action="{{ route('leaves.store') }}" method="POST">
        @csrf
        @include('leaves.form')
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
```

**resources/views/leaves/edit.blade.php**

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Leave Request</h2>
    <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('leaves.form')
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection