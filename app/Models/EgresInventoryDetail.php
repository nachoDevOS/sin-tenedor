<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;
use PhpParser\Node\Expr\FuncCall;

class EgresInventoryDetail extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'egresInventory_id',
        'item_id',
        'dispensingType',
        'quantity',
        'observation',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function itemInventory()
    {
        return $this->belongsTo(ItemInventory::class, 'item_id');
    }

    public function egresDetailItemInventoryStock()
    {
        return $this->hasMany(EgresDetailItemInventoryStock::class, 'egresDetail_id');
    }
}
