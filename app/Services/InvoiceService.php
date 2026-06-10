<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Receivable;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InvoiceService
{
    public function createInvoice(array $data, array $items)
    {
        return DB::transaction(function () use ($data, $items) {
            $shipment = Shipment::findOrFail($data['shipment_id']);
            
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['total_price'];
            }
            
            $tax = $subtotal * ($data['tax_rate'] ?? 0.11); // Default tax 11%
            $grandTotal = $subtotal + $tax;

            $invoice = Invoice::create([
                'shipment_id' => $shipment->id,
                'customer_id' => $shipment->customer_id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => $data['invoice_date'] ?? now(),
                'due_date' => $data['due_date'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'status' => 'draft',
                'user_id' => Auth::id() ?? 1,
            ]);

            foreach ($items as $item) {
                $invoice->items()->create($item);
            }

            return $invoice;
        });
    }

    public function finalizeInvoice($id)
    {
        return DB::transaction(function () use ($id) {
            $invoice = Invoice::findOrFail($id);
            $invoice->update(['status' => 'sent']);

            // Create Receivable record
            Receivable::updateOrCreate(['invoice_id' => $invoice->id], [
                'customer_id' => $invoice->customer_id,
                'amount_due' => $invoice->grand_total,
                'amount_paid' => 0,
                'status' => 'outstanding',
            ]);

            return $invoice;
        });
    }

    protected function generateInvoiceNumber()
    {
        $prefix = 'INV/' . date('Y') . '/';
        $last = Invoice::where('invoice_number', 'like', $prefix . '%')->latest()->first();
        
        if ($last) {
            $num = (int) str_replace($prefix, '', $last->invoice_number);
            $newNum = str_pad($num + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNum = '00001';
        }

        return $prefix . $newNum;
    }
}
