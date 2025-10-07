<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->nullable()->constrained('cashiers');
            // $table->foreignId('user_id')->nullable()->constrained('users');
            // $table->foreignId('cashier_movement_category_id')->nullable()->constrained('cashier_movement_categories');
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable(); //ingreso o egreso
            $table->string('status')->nullable(); //aprobado, pendiente, rechazado

            // $table->foreignId('transferCashier_id')->nullable()->constrained('cashiers');


            $table->timestamps();            
            $table->foreignId('registerUser_id')->nullable()->constrained('users');
            $table->string('registerRole')->nullable();

            $table->softDeletes();
            $table->foreignId('deleteUser_id')->nullable()->constrained('users');
            $table->string('deleteRole')->nullable();
            $table->text('deleteObservation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashier_movements');
    }
};
