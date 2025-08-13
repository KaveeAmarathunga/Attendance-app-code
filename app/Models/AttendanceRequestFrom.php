<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequestFrom extends Model
{
    use HasFactory;

    protected $table = 'attendance_request_froms'; // Change if your table name differs

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'requested_epf_number',
        'attendance_id'
    ];

    /**
     * Relationship: Attendance request belongs to a user (requested by)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'epf_number', 'epf_number');
    }

    /**
     * Relationship: Attendance request was approved by a user
     */
    // public function approvedBy()
    // {
    //     return $this->belongsTo(User::class, 'approved_by', 'epf_number');
    // }
}
