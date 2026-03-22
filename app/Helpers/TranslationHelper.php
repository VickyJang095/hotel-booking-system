<?php

namespace App\Helpers;

class TranslationHelper
{
    // ── AMENITIES ──────────────────────────────────────────────
    public static function amenity(string $value): string
    {
        return self::amenities()[$value] ?? $value;
    }

    public static function amenities(): array
    {
        return [
            'Wi-Fi'                  => 'Wi-Fi',
            'Pool'                   => 'Hồ bơi',
            'Gym'                    => 'Phòng gym',
            'Restaurant'             => 'Nhà hàng',
            'Spa access'             => 'Spa',
            'Spa'                    => 'Spa',
            'Free Parking'           => 'Đỗ xe miễn phí',
            'Air Conditioning'       => 'Điều hòa',
            'TV'                     => 'TV',
            'Mini Bar'               => 'Mini bar',
            'Room Service'           => 'Dịch vụ phòng',
            'Breakfast'              => 'Bữa sáng',
            'Breakfast Included'     => 'Bao gồm bữa sáng',
            'Concierge'              => 'Lễ tân 24h',
            'Laundry'                => 'Giặt ủi',
            'Pet Friendly'           => 'Cho phép thú cưng',
            'Iron'                   => 'Bàn ủi',
            'Hair dryer'             => 'Máy sấy tóc',
            'Hair Dryer'             => 'Máy sấy tóc',
            'Smoke Alarm'            => 'Báo khói',
            'Carbon monoxide Alarm'  => 'Báo khí CO',
            'Carbon Monoxide Alarm'  => 'Báo khí CO',
            'Kitchen'                => 'Bếp',
            'BBQ'                    => 'BBQ',
            'Washer'                 => 'Máy giặt',
            'Safe'                   => 'Két an toàn',
            'Towels'                 => 'Khăn tắm',
            'Hangers'                => 'Móc treo đồ',
            'Heating'                => 'Sưởi ấm',
            'Balcony'                => 'Ban công',
            'Sea View'               => 'View biển',
            'City View'              => 'View thành phố',
            'Mountain View'          => 'View núi',
            'Bathtub'                => 'Bồn tắm',
            'Jacuzzi'                => 'Jacuzzi',
            'Private bathroom'       => 'Phòng tắm riêng',
            'Flat-screen TV'         => 'TV màn hình phẳng',
            'Safe box'               => 'Két an toàn',
            'Work desk'              => 'Bàn làm việc',
            'Coffee maker'           => 'Máy pha cà phê',
            'Living Room'            => 'Phòng khách',
            'Wheelchair Accessible'  => 'Thân thiện xe lăn',
            'Free WiFi'              => 'WiFi miễn phí',
            'Hot tub'                => 'Bồn tắm nước nóng',
            'Bar'                    => 'Bar',
            'Beach access'           => 'Bãi biển riêng',
            'Waterfront'             => 'Ven biển',
        ];
    }

    // ── ROOM TYPES ─────────────────────────────────────────────
    public static function roomType(string $value): string
    {
        return self::roomTypes()[$value] ?? $value;
    }

    public static function roomTypes(): array
    {
        return [
            'Standard'  => 'Tiêu chuẩn',
            'Deluxe'    => 'Deluxe',
            'Superior'  => 'Superior',
            'Suite'     => 'Suite',
            'Family'    => 'Gia đình',
            'Twin'      => 'Twin',
            'Single'    => 'Đơn',
            'Double'    => 'Đôi',
            'Triple'    => 'Ba người',
            'Quad'      => 'Bốn người',
            'Studio'    => 'Studio',
            'Penthouse' => 'Penthouse',
        ];
    }

    // ── BED TYPES ──────────────────────────────────────────────
    public static function bedType(string $value): string
    {
        return self::bedTypes()[$value] ?? $value;
    }

    public static function bedTypes(): array
    {
        return [
            'Single bed'   => 'Giường đơn',
            'Double bed'   => 'Giường đôi',
            'Queen bed'    => 'Giường Queen',
            'King bed'     => 'Giường King',
            'King Bed'     => 'Giường King',
            'Twin beds'    => '2 giường đơn',
            'Bunk bed'     => 'Giường tầng',
            '2 Double Beds' => '2 giường đôi',
            '1 King + 2 Single' => '1 King + 2 đơn',
        ];
    }

    // ── PROPERTY TYPES ─────────────────────────────────────────
    public static function propertyType(string $value): string
    {
        return self::propertyTypes()[$value] ?? $value;
    }

    public static function propertyTypes(): array
    {
        return [
            'Hotel'       => 'Khách sạn',
            'Hostel'      => 'Nhà trọ',
            'Apartment'   => 'Căn hộ',
            'Villa'       => 'Biệt thự',
            'Resort'      => 'Khu nghỉ dưỡng',
            'Guesthouse'  => 'Nhà nghỉ',
            'Motel'       => 'Motel',
            'Capsule'     => 'Khách sạn Capsule',
            'Homestay'    => 'Homestay',
            'Bungalow'    => 'Bungalow',
        ];
    }

    // ── PAYMENT METHODS ────────────────────────────────────────
    public static function paymentMethod(string $value): string
    {
        return self::paymentMethods()[$value] ?? $value;
    }

    public static function paymentMethods(): array
    {
        return [
            'credit_card'  => 'Thẻ tín dụng',
            'debit_card'   => 'Thẻ ghi nợ',
            'paypal'       => 'PayPal',
            'vnpay'        => 'VNPay',
            'momo'         => 'MoMo',
            'cash'         => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'Credit Card'  => 'Thẻ tín dụng',
            'Debit Card'   => 'Thẻ ghi nợ',
            'Cash'         => 'Tiền mặt',
            'VNPay'        => 'VNPay',
            'MoMo'         => 'MoMo',
        ];
    }

    // ── BOOKING STATUS ─────────────────────────────────────────
    public static function bookingStatus(string $value): string
    {
        return self::bookingStatuses()[$value] ?? $value;
    }

    public static function bookingStatusColor(string $value): string
    {
        return [
            'pending'     => 'amber',
            'confirmed'   => 'blue',
            'checked_in'  => 'green',
            'checked_out' => 'gray',
            'cancelled'   => 'red',
        ][$value] ?? 'gray';
    }

    public static function bookingStatuses(): array
    {
        return [
            'pending'     => 'Chờ xác nhận',
            'confirmed'   => 'Đã xác nhận',
            'checked_in'  => 'Đã nhận phòng',
            'checked_out' => 'Đã trả phòng',
            'cancelled'   => 'Đã hủy',
        ];
    }
}
