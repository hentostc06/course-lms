<?php

use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->unique();
            $table->timestampsTz();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->enum('status', array_column(InstructorStatus::cases(), 'value'))
                ->default(InstructorStatus::Pending->value)
                ->index();
            $table->timestampTz('approved_at')->nullable();
            $table->timestampsTz();
        });

        $now = now();

        DB::table('roles')->insert(array_map(
            fn (RoleName $role): array => [
                'name' => $role->value,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            RoleName::cases(),
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
