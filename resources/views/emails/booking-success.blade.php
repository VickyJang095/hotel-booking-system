<h2>Đặt phòng thành công 🎉</h2>

<p>Xin chào {{ $booking->guest_name }},</p>

<p>Bạn đã đặt phòng thành công!</p>

<ul>
    <li>Mã booking: {{ $booking->booking_code }}</li>
    <li>Check-in: {{ $booking->check_in }}</li>
    <li>Check-out: {{ $booking->check_out }}</li>
    <li>Tổng tiền: {{ number_format($booking->total_amount) }} USD</li>
</ul>

<p>Cảm ơn bạn đã sử dụng dịch vụ ❤️</p>