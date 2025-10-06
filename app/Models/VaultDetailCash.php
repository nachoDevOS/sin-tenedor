<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class VaultDetailCash extends Model
{
    use HasFactory, SoftDeletes, RegistersUserEvents;
    protected $fillable = [
        'vault_detail_id', 'cash_value', 'quantity',
        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation'
    ];
}
