<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies'; // Optional: set if not plural

    // protected $primaryKey = 'company_id';
    // public $incrementing = false;
    // protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'name',
        'location',
        'address',
        'number_of_employees',
    ];


    /**
     * Relationship: Company has many Users (employees).
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }
}
