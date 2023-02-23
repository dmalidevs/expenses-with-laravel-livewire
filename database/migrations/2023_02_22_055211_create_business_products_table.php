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
        Schema::create('business_products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('details');
            $table->double('price',8,2);
            $table->string('image')->nullable();
            $table->integer('quantity');
            $table->boolean('status')->default(false);
            $table->foreignId('category_id')->constrained('business_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained('business_sub_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('business_producs');
    }
};
