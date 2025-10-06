<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class Vault extends Model
{
    use HasFactory, SoftDeletes, RegistersUserEvents;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'closed_at',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function details(){
        return $this->hasMany(VaultDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
