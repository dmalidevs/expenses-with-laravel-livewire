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
        Schema::create('expenses_bills', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('purpose');
            $table->text('details');
            $table->double('amount',8,2);
            $table->string('invoice_image')->nullable();
            $table->timestamp('billing_date')->nullable();
            $table->foreignId('type_id')->constrained('expenses_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('assigned_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses_bills');
    }
};
