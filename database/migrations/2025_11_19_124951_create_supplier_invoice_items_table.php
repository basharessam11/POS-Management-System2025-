<?php

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
        Schema::create('supplier_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('supplier_invoices')->onDelete('cascade');
            $table->foreignId('product_item_id')->constrained('product_items')->onDelete('cascade');
            $table->integer('qty');   // الكمية
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes(); // ✅ هذا السطر يضيف عمود deleted_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_items');
    }
};
