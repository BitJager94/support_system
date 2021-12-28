<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            
            $table->id();
            $table->text('text');
            $table->enum('status', ['not-answered', 'in-progress', 'answered'])->default('not-answered');
            $table->boolean('spam')->default(false);
            $table->datetime('latest_reply_date');
            $table->timestamps();

            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
