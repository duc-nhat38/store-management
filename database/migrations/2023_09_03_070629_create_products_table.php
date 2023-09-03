<?php

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 100)->index();
            $table->string('name')->index();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('trademark_id')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('price', 16, 2)->nullable();
            $table->string('currency', 100)->nullable();
            $table->string('origin')->nullable();
            $table->unsignedTinyInteger('status')->default(ProductStatus::AVAILABLE);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('trademark_id')->references('id')->on('trademarks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
