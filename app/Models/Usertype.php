<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usertype extends Model
{
    use HasFactory;

    protected $table = 'usertype';
    protected $primaryKey = 'usertype_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['usertype_id', 'usertype_name'];

    // 🧩 Relationship: A Usertype has many Users
    public function users()
    {
        return $this->hasMany(User::class, 'usertype_id', 'usertype_id');
    }
}
