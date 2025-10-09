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
        'cashier_id',
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


    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->code = self::generateCode();
        });
    }

    public static function generateCode(): string
    {
        $date = now()->format('Ym');
        $lastSale = self::withTrashed()
                        // ->whereDate('created_at', today())
                        ->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->orderBy('id', 'desc')
                        ->first();
        
        $sequence = $lastSale ? 
            (int) substr($lastSale->code, -5) + 1 : 
            1;
            
        return 'VTA-' . $date . str_pad($sequence, 5, '0', STR_PAD_LEFT);
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

    public function saleTransactions()
    {
        return $this->hasMany(SaleTransaction::class, 'sale_id');
    }
    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }


}
