<?php

namespace App\Models;

use App\Enums\UserGroupStatus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'color',
        'desc',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group_term', 'user_id', 'group_id');
    }
}
