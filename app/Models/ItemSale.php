<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class ItemSale extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'category_id',
        'image',
        'name',
        'price',
        'observation',
        'typeSale',
        'status',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    public function itemSalestocks()
    {
        return $this->hasMany(ItemSaleStock::class, 'itemSale_id');
    }


}
