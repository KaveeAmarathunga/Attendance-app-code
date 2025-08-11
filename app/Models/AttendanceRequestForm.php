<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequestForm extends Model
{
    use HasFactory;

    protected $table = 'attendance_request_forms'; // Change if your table name differs

    protected $primaryKey = 'request_id'; // Assuming this is the PK
    public $incrementing = false;         // Assuming it's a string or manually set
    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'request_id',
        'epf_number',
        'date',
        'in_time',
        'out_time',
        'reason',
        'status',
        'approved_by'
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
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'epf_number');
    }
}
