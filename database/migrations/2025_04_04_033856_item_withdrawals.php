<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->date('withdrawal_date');
            $table->foreignId('taken_by')->constrained('users');
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_withdrawals');
    }
};