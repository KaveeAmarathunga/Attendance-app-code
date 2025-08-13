<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leave'; // Optional: set if your table name isn't pluralized

    protected $primaryKey = 'leave_id';

    public $incrementing = true;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'leave_id',
        'epf_number',
        'consider_by',
        'from_date',
        'to_date',
        'status',
        'reason',
        'paid',
        'leavetype_id'
    ];

    /**
     * Relationship: Leave belongs to a LeaveType.
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leavetype_id', 'leavetype_id');
    }

    /**
     * Relationship: Leave belongs to a User (requested by).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'epf_number', 'epf_number');
    }

    /**
     * Relationship: Leave was approved by a User (admin or manager).
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'consider_by', 'epf_number');
    }

    public function leaveRequestFrom()
    {
        return $this->hasMany(LeaveRequestFrom::class,'leave_id','leave_id');
    }
}
