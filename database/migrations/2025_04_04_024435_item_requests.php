<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('quantity')->nullable();
            $table->enum('status', [
                Status::PENDING->value,
                Status::APPROVED->value,
                Status::REJECTED->value,
            ])->default(Status::PENDING->value);

            $table->integer('approved_by')->nullable(); // harus sama dengan users.nip
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('approved_by')
                ->references('nip')
                ->on('users')
                ->onDelete('set null'); // aman dan fleksibel
        });
    }



    public function down()
    {
        Schema::dropIfExists('item_requests');
    }
};
