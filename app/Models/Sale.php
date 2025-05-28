<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class Sale extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'person_id',
        'code',
        'ticket',
        'typeSale',
        'amountReceived',
        'amountChange',
        'amount',
        'observation',
        'dateSale',
        'status',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

/**
     * Boot del modelo para generar código y ticket automáticamente.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->code = self::generateCode();
        });
    }

    /**
     * Genera el código de venta en formato VENT-YYYYMMDD-XXXXX.
     */
    public static function generateCode(): string
    {
        $date = now()->format('Ymd');
        $lastSale = self::withTrashed()
                        ->whereDate('created_at', today())
                        ->orderBy('id', 'desc')
                        ->first();
        
        $sequence = $lastSale ? 
            (int) substr($lastSale->code, -5) + 1 : 
            1;
            
        return 'VENT-' . $date . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }



    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }
    
    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id')->withTrashed();
    }


}
