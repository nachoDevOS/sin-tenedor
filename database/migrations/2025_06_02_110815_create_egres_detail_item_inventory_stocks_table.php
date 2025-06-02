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
        Schema::create('egres_detail_item_inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egresDetail_id')->nullable()->constrained('egres_inventory_details');
            $table->foreignId('itemInventoryStock_id')->nullable()->constrained('item_inventory_stocks');

            $table->decimal('quantity', 10, 2)->nullable();

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
        Schema::dropIfExists('egres_detail_item_inventory_stocks');
    }
};
