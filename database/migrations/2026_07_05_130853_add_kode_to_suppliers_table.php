<?php

use App\Models\Supplier;
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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('kode', 20)->nullable()->after('id');
        });

        Supplier::each(function ($supplier) {
            $initial = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $supplier->nama), 0, 3));
            $supplier->kode = $initial;
            $supplier->save();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('kode', 20)->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
