<h2>🎉 Booking đã được xác nhận</h2>

<p>Xin chào {{ $booking->guest_name }},</p>

<p>Khách sạn đã xác nhận đặt phòng của bạn.</p>

<ul>
    <li>Mã booking: {{ $booking->booking_code }}</li>
    <li>Check-in: {{ $booking->check_in }}</li>
    <li>Check-out: {{ $booking->check_out }}</li>
</ul>

<p>Chúc bạn có chuyến đi tuyệt vời ❤️</p>