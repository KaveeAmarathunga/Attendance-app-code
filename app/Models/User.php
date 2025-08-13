<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'epf_number',
        'usertype_id',
        'designation',
        'company_id',
        'date_of_birth',
        'date_of_append',
        'date_of_resign',
        'insurance',
        'blood_type',
        'b_card_status',
        'office_phonenumber',
        'personal_phonenumber',
        'emergency_phonenumber'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function usertype()
    {
        return $this->belongsTo(UserType::class, 'usertype_id', 'usertype_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'epf_number', 'epf_number');
    }


    public function suspenses()
    {
        return $this->hasMany(Suspense::class, 'epf_number', 'epf_number');
    }

    // public function leaveRequestsFrom()
    // {
    //     return $this->hasMany(LeaveRequest::class, 'requested_epf_number', 'epf_number');
    // }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'epf_number', 'epf_number');
    }
}
