<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();

            // Thông tin đặt phòng
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');
            $table->integer('rooms')->default(1);
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);

            // Thông tin khách
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 20)->nullable();
            $table->text('special_requests')->nullable();

            // Giá tiền
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('USD');

            // Thanh toán
            $table->enum('payment_method', ['credit_card', 'debit_card', 'paypal', 'vnpay', 'momo', 'cash'])->default('credit_card');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Trạng thái đặt phòng
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->text('cancel_reason')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Add-ons
            $table->boolean('travel_insurance')->default(false);
            $table->boolean('phone_confirmation')->default(false);

            // Mã đặt phòng
            $table->string('booking_code')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
