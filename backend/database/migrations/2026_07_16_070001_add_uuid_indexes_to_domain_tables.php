<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('invitations', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('invitation_templates', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('guests', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('rsvps', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('checklists', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('checklist_items', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('timelines', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('timeline_tasks', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('budgets', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('budget_categories', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('budget_transactions', function (Blueprint $table): void {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('invitations', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('invitation_templates', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('guests', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('rsvps', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('checklists', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('checklist_items', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('timelines', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('timeline_tasks', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('budgets', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('budget_categories', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('budget_transactions', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });
    }
};
