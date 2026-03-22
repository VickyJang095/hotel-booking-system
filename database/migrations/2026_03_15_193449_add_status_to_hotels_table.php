<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->enum('status', ['pending_review', 'approved', 'rejected'])
                ->default('pending_review')
                ->after('owner_id');
            $table->text('reject_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn(['status', 'reject_reason']);
        });
    }
};
