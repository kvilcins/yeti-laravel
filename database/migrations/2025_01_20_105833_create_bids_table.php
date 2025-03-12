<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id(); // Автоматический ID
            $table->unsignedBigInteger('lot_id'); // ID связанного лота (товара)
            $table->unsignedBigInteger('user_id'); // ID пользователя, сделавшего ставку
            $table->decimal('bid_amount', 10, 2); // Сумма ставки
            $table->timestamp('bid_time')->useCurrent(); // Время ставки
            $table->timestamps(); // Для created_at и updated_at
            
            // Внешние ключи
            $table->foreign('lot_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bids');
    }
}
