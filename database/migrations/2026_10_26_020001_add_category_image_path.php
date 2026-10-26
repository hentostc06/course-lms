<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (! Schema::hasColumn('categories', 'image_path')) {
            Schema::table('categories', fn (Blueprint $table) =>
                $table->string('image_path', 255)->nullable());
        }
    }

    public function down(): void {
        if (Schema::hasColumn('categories', 'image_path')) {
            Schema::table('categories', fn (Blueprint $table) =>
                $table->dropColumn('image_path'));
        }
    }
};
