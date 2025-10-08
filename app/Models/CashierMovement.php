<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class CashierMovement extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'cashier_id', 'amount', 'description', 'type', 'deleted_at', 'status',
        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function cashier(){
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    // public function cashier_from(){
    //     return $this->belongsTo(Cashier::class, 'cashier_id_from');
    // }

    // public function cashier_to(){
    //     return $this->belongsTo(Cashier::class, 'cashier_id_to');
    // }

    public function user(){
        return $this->belongsTo(User::class, 'registerUser_id');
    }
    // public function cashierMovementCategory()
    // {
    //     return $this->belongsTo(CashierMovementCategory::class, 'cashier_movement_category_id')->withTrashed();
    // }
}
