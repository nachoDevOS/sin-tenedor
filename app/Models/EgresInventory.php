<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class EgresInventory extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'dateEgres',
        'observation',
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
                        ->orderBy('id', 'desc')
                        ->first();
        
        $sequence = $lastSale ? 
            (int) substr($lastSale->code, -5) + 1 : 
            1;
            
        return $date . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id')->withTrashed();
    }
    
    public function egresInventoryDetails()
    {
        return $this->hasMany(EgresInventoryDetail::class, 'egresInventory_id');
    }


}
