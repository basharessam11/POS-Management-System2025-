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
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
                $table->softDeletes(); // ✅ هذا السطر يضيف عمود deleted_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
    }
};
