<?php

namespace App\Mail;

// app/Mail/BookingSuccessMail.php

use Illuminate\Mail\Mailable;

class BookingSuccessMail extends Mailable
{
    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Xác nhận đặt phòng thành công')
            ->view('emails.booking-success');
    }
}
