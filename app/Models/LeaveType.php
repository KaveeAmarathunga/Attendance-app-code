<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_type';

    protected $primaryKey = 'leavetype_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'leavetype_id',
        'number_of_leaves',
        'leave_type_name',
    ];

    /**
     * A leave type can be associated with many leaves.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'leavetype_id', 'leavetype_id');
    }
}
