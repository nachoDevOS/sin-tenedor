<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class ItemSaleStock extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'itemSale_id',
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

    public function itemSale()
    {
        return $this->belongsTo(ItemSale::class, 'itemSale_id')->withTrashed();
    }
    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id');
    }



}
