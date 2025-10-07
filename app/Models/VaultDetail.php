<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;
use App\Models\Cashier;

class VaultDetail extends Model
{
    use HasFactory, SoftDeletes, RegistersUserEvents;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'vault_id', 'cashier_id', 'bill_number', 'name_sender', 'description', 'type', 'status',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];


    public function cash(){
        return $this->hasMany(VaultDetailCash::class);
    }

    // public function user(){
    //     return $this->belongsTo(User::class)->withTrashed();
    // }

    public function cashier(){
        return $this->belongsTo(Cashier::class);
    }
}
