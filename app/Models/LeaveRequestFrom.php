<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestFrom extends Model
{
    use HasFactory;

    protected $table = 'leave_request_froms';

    protected $primaryKey = 'id'; // Optional: Laravel uses 'id' by default

    public $incrementing = true;

    protected $fillable = [
        'requested_epf_number',
        'leave_id'
    ];

    /**
     * Relationship: Leave request belongs to a user (by EPF number).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'requested_epf_number', 'epf_number');
    }

    /**
     * Relationship: Leave request belongs to a leave type (via leave_id).
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_id', 'leavetype_id');
    }
}
