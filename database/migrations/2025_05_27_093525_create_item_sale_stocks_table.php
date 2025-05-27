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
        Schema::create('item_sale_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itemSale_id')->nullable()->constrained('item_sales');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('stock', 10, 2)->nullable();
            $table->string('type')->nullable(); //Ingreso, Egreso
            $table->text('observation')->nullable();

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
        Schema::dropIfExists('item_sale_stocks');
    }
};
