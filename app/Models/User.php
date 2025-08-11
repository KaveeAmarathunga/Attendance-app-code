<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'epf_number', 'usertype_id'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function usertype()
    {
        return $this->belongsTo(Usertype::class, 'usertype_id', 'usertype_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'epf_number', 'epf_number');
    }

    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by', 'epf_number');
    }

    public function suspenses()
    {
        return $this->hasMany(Suspense::class, 'epf_number', 'epf_number');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'requested_epf_number', 'epf_number');
    }
}
