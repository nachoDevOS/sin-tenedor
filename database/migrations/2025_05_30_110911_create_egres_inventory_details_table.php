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
        Schema::create('egres_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egresInventory_id')->nullable()->constrained('egres_inventories');

            $table->foreignId('item_id')->nullable()->constrained('item_inventories');
            $table->string('dispensingType')->nullable();

            $table->decimal('quantity', 10, 2)->nullable();
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
        Schema::dropIfExists('egres_inventory_details');
    }
};
