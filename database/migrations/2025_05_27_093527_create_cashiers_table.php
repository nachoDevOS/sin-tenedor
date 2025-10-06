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
        Schema::create('cashiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vault_id')->nullable()->constrained('vaults');
            $table->foreignId('user_id')->nullable()->constrained('users');  // Cajero 
            $table->string('title')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('observations')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('view')->nullable(); 

            $table->datetime('closed_at')->nullable();
            $table->foreignId('closeUser_id')->nullable()->constrained('users');

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
        Schema::dropIfExists('cashiers');
    }
};
