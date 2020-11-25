<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'user_type',
        'user_name',
        'user_document',
        'user_email',
        'user_wallet'
    ];

    protected $hidden = [
        'password',
    ];

    protected $dates = [
        'deleted_at'
    ];
}
