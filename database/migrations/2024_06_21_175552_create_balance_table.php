<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceTable extends Migration
{
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->default(0);

            $table->unsignedBigInteger('user_id')->unique(); // разрешаем значение null
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
