<?php

namespace App\Notifications;

use App\Models\Reservation;
use chillerlan\QRCode\QRCode;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

class ReservationPlaced extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Reservation $reservation)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::frontend()->reservation($this->reservation);

        $qrcode = (new QRCode)->render($url);

        return (new MailMessage)
            ->line('The reservation has been placed.')
            ->line('Movie: ' . $this->reservation->movie?->title)
            ->line('Time: ' . $this->reservation->showing?->starts_at->toDateTimeString())
            ->line('Seats: ' . json_encode($this->reservation->seats))
            ->line('Please show QR Code below to the cinema staff:')
            ->line(new HtmlString(<<<HTML
                    <img src="$qrcode" alt="QR Code" style="margin: 0 auto;"/>
                    HTML))
            ->line('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
