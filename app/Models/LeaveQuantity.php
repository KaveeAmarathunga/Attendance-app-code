<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveQuantity extends Model
{
    use HasFactory;

    protected $table = 'leave_quantity';

    public $timestamps = false;

    protected $primaryKey = 'id'; // Optional: use 'id' if it's the default

    public $incrementing = true;

    protected $fillable = [
        'epf_number',
        'leavetype_id',
        'total_leaves',
        'used_leaves',
        'remaining_leaves'
    ];

    /**
     * Relationship: LeaveQuantity belongs to a user (by EPF number).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'epf_number', 'epf_number');
    }

    /**
     * Relationship: LeaveQuantity belongs to a leave type.
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id', 'leavetype_id');
    }
}
