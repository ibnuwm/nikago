<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique();
            $table->foreignUuid('tenant_id')->nullable()->after('uuid');
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->string('status', 20)->default('active')->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes();
            $table->foreignUuid('created_by')->nullable()->after('deleted_at');
            $table->foreignUuid('updated_by')->nullable()->after('created_by');
            $table->foreignUuid('deleted_by')->nullable()->after('updated_by');
        });

        DB::table('users')->whereNull('uuid')->orderBy('id')->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update(['uuid' => Str::uuid()->toString()]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'tenant_id',
                'avatar',
                'phone',
                'status',
                'last_login_at',
                'deleted_at',
                'created_by',
                'updated_by',
                'deleted_by',
            ]);
        });
    }
};
