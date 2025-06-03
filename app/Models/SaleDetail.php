<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class SaleDetail extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sale_id',
        'item_id',
        'typeSaleItem',
        'price',
        'quantity',
        'amount',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function itemSale()
    {
        return $this->belongsTo(ItemSale::class, 'item_id')->withTrashed();
    }

    public function saleDetailItemSaleStock()
    {
        return $this->hasMany(SaleDetailItemSaleStock::class, 'saleDetail_id');
    }


}
