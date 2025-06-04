<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class ItemInventoryStock extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'itemInventory_id',
        'quantity',
        'stock',
        'type',
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
        return $this->belongsTo(ItemInventory::class, 'itemInventory_id')->withTrashed();
    }

    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id');
    }
}
