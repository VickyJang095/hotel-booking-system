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
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('type')->default('Hotel');
            $table->integer('star_rating')->default(3);
            $table->integer('review_count')->default(0);
            $table->decimal('distance_from_centre', 4, 1)->default(1.0); // km
            $table->boolean('free_cancellation')->default(false);
            $table->boolean('instant_booking')->default(false);
            $table->boolean('pay_at_property')->default(false);
            $table->boolean('pay_later')->default(false);
            $table->boolean('wheelchair_accessible')->default(false);
            $table->json('amenities')->nullable();       // ["Wi-Fi","Pool","Gym"]
            $table->json('payment_methods')->nullable(); // ["Credit Card","Cash"]
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'star_rating',
                'review_count',
                'distance_from_centre',
                'free_cancellation',
                'instant_booking',
                'pay_at_property',
                'pay_later',
                'wheelchair_accessible',
                'amenities',
                'payment_methods',
            ]);
        });
    }
};
