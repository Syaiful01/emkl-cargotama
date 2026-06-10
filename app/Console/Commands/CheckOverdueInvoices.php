<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emkl:check-overdue';
    protected $description = 'Check for overdue invoices and send notifications';

    public function handle()
    {
        $overdueInvoices = \App\Models\Invoice::where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->get();

        foreach ($overdueInvoices as $invoice) {
            // Notify Customer (PIC) and internal Finance
            // For now, let's notify the creator of the invoice
            $invoice->user->notify(new \App\Notifications\InvoiceOverdueNotification($invoice));
            
            $this->info('Notified for invoice: ' . $invoice->invoice_number);
        }

        return 0;
    }
}
