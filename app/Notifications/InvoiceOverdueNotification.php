<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceOverdueNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Overdue Invoice: ' . $this->invoice->invoice_number)
                    ->line('The following invoice is overdue:')
                    ->line('Invoice Number: ' . $this->invoice->invoice_number)
                    ->line('Amount: ' . number_format($this->invoice->grand_total, 2))
                    ->line('Due Date: ' . $this->invoice->due_date->format('d M Y'))
                    ->action('View Invoice', url('/invoices/' . $this->invoice->id))
                    ->line('Please settle the payment immediately.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->invoice_number,
            'amount' => $this->invoice->grand_total,
            'message' => 'Invoice ' . $this->invoice->invoice_number . ' is overdue.',
        ];
    }

}
