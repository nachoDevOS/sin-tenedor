<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class VaultClosure extends Model
{
    use HasFactory, SoftDeletes, RegistersUserEvents;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vault_id', 'observations',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function details(){
        return $this->hasMany(VaultClosureDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'registerUser_id');
    }


}
