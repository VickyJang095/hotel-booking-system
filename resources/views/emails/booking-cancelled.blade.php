<h2>❌ Booking bị từ chối</h2>

<p>Xin chào {{ $booking->guest_name }},</p>

<p>Rất tiếc, khách sạn đã từ chối booking của bạn.</p>

<p>Bạn có thể thử đặt phòng khác.</p>

<ul>
    <li>Mã booking: {{ $booking->booking_code }}</li>
</ul>