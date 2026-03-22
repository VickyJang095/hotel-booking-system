<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('avatar')->nullable()->after('last_name');
            $table->string('address')->nullable()->after('avatar');
            $table->string('city')->nullable()->after('address');
            $table->date('date_of_birth')->nullable()->after('city');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');

            // Thông tin thanh toán
            $table->string('card_holder_name')->nullable()->after('gender');
            $table->string('card_number_masked')->nullable()->after('card_holder_name'); // chỉ lưu 4 số cuối
            $table->string('card_expiry')->nullable()->after('card_number_masked');
            $table->string('card_type')->nullable()->after('card_expiry'); // visa, mastercard...
            $table->string('billing_address')->nullable()->after('card_type');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_postal_code')->nullable()->after('billing_city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'avatar',
                'address',
                'city',
                'date_of_birth',
                'gender',
                'card_holder_name',
                'card_number_masked',
                'card_expiry',
                'card_type',
                'billing_address',
                'billing_city',
                'billing_postal_code',
            ]);
        });
    }
};
