<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances'; // Adjust if different
    protected $primaryKey = 'attendance_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'attendance_id',
        'epf_number',
        'date',
        'check_in',
        'check_out',
        'check_in_approved_by',
        'check_out_approved_by',
        'morning_allowence',
        'evening_allowence'
    ];

    /**
     * Attendance belongs to a user (who this attendance is for)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'epf_number', 'epf_number');
    }

    /**
     * The user who approved the check-in
     */
    public function checkInApprover()
    {
        return $this->belongsTo(User::class, 'check_in_approved_by', 'epf_number');
    }

    /**
     * The user who approved the check-out
     */
    public function checkOutApprover()
    {
        return $this->belongsTo(User::class, 'check_out_approved_by', 'epf_number');
    }

    /**
     * One attendance can have multiple related requests
     */
    public function requests()
    {
        return $this->hasMany(AttendanceRequestForm::class, 'attendance_id');
    }
}
