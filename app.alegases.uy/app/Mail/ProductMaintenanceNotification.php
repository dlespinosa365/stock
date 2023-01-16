<?php

namespace App\Mail;

use App\Models\ProductMaintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductMaintenanceNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ProductMaintenance $productMaintenance;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductMaintenance $productMaintenance)
    {
        $this->productMaintenance = $productMaintenance;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Notificacion de Mantenimiento',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.product-maintenance-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
