<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->enum('operation_type', ['credit', 'debit']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            $table->unsignedBigInteger('balance_id');
            $table->foreign('balance_id')->references('id')->on('balances')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operations');
    }
}
