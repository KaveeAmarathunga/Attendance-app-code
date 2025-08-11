<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspense extends Model
{
    use HasFactory;

    // Optional: If table name doesn't follow Laravel's plural convention
    protected $table = 'suspenses';

    // Disable timestamps if the table doesn't have created_at and updated_at
    public $timestamps = false;

    protected $fillable = [
        'epf_number',
        'amount',
    ];

    // Relationship: Each suspense record belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class, 'epf_number', 'epf_number');
    }
}
