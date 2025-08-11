<div class="form-group mb-3">
    <label for="epf_number">EPF Number</label>
    <input type="text" name="epf_number" class="form-control" value="{{ old('epf_number', $leave->epf_number ?? '') }}" required>
</div>
<div class="form-group mb-3">
    <label for="approved_by">Approved By</label>
    <input type="text" name="approved_by" class="form-control" value="{{ old('approved_by', $leave->approved_by ?? '') }}" required>
</div>
<div class="form-group mb-3">
    <label for="from_date">From Date</label>
    <input type="date" name="from_date" class="form-control" value="{{ old('from_date', $leave->from_date ?? '') }}" required>
</div>
<div class="form-group mb-3">
    <label for="to_date">To Date</label>
    <input type="date" name="to_date" class="form-control" value="{{ old('to_date', $leave->to_date ?? '') }}" required>
</div>
<div class="form-group mb-3">
    <label for="status">Status</label>
    <select name="status" class="form-control">
        <option value="pending" {{ (old('status', $leave->status ?? '') == 'pending') ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ (old('status', $leave->status ?? '') == 'approved') ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ (old('status', $leave->status ?? '') == 'rejected') ? 'selected' : '' }}>Rejected</option>
    </select>
</div>
<div class="form-group mb-3">
    <label for="paid">Paid</label>
    <select name="paid" class="form-control">
        <option value="1" {{ (old('paid', $leave->paid ?? '') == 1) ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ (old('paid', $leave->paid ?? '') == 0) ? 'selected' : '' }}>No</option>
    </select>
</div>
<div class="form-group mb-3">
    <label for="leavetype_id">Leave Type</label>
    <select name="leavetype_id" class="form-control">
        @foreach ($leaveTypes as $type)
            <option value="{{ $type->id }}" {{ (old('leavetype_id', $leave->leavetype_id ?? '') == $type->id) ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>