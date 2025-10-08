<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class Cashier extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'vault_id', 'user_id', 'title', 'observations', 'status', 'closed_at', 'deleted_at', 'closeUser_id', 'view',
        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function movements(){
        return $this->hasMany(CashierMovement::class, 'cashier_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');//Para el cajero 
    }


    public function vault_details(){
        return $this->hasMany(VaultDetail::class, 'cashier_id');
    }
    public function vault_detail(){
        return $this->hasOne(VaultDetail::class, 'cashier_id');
    }

    public function sales(){
        return $this->hasMany(Sale::class, 'cashier_id')->withTrashed();
    }

    public function details(){
        return $this->hasMany(CashierDetail::class);
    }

    public function userclose(){
        return $this->belongsTo(User::class, 'closeUser_id');
    }

 

    // public function vault()
    // {
    //     return $this->belongsTo(Vault::class, 'vault_id');
    // }


    // public function salaryPurchase()
    // {
    //     return $this->hasMany(SalaryPurchase::class, 'cashier_id');
    // }
    // public function salaryPurchasePayment(){
    //     return $this->hasMany(SalaryPurchaseMonthAgent::class,'cashier_id');
    // }




    // public function loan_payments(){
    //     return $this->hasMany(LoanDayAgent::class);
    // }

    // public function loans(){
    //     return $this->hasMany(Loan::class);
    // }

    // public function pawn(){
    //     return $this->hasMany(PawnRegister::class, 'cashier_id');
    // }

    // public function pawnPayment(){
    //     return $this->hasMany(PawnRegisterMonthAgent::class,'cashier_id');
    // }

    // public function pawnMoneyAditional()
    // {
    //     return $this->hasMany(PawnRegisterAmountAditional::class,'cashier_id');
    // }

    // public function salePayment(){
    //     return $this->hasMany(SaleAgent::class);
    // }

    // //_:::::::::::::
    // //Para obtener uno 
    
    // //para obtener todoooo
    // public function vault_detail(){
    //     return $this->hasMany(VaultDetail::class, 'cashier_id');
    // }
    //_:::::::::::::

    // public function client(){
    //     return $this->hasMany(Client::class);
    // }

}
