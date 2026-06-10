<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $invoices = \App\Models\Invoice::with(['customer', 'shipment'])->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($invoices);
        }

        $shipments = \App\Models\Shipment::orderBy('job_number', 'asc')->get();

        return view('invoices.index', compact('invoices', 'shipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'due_date' => 'required|date',
            'tax_rate' => 'nullable|numeric',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total_price' => 'required|numeric',
        ]);

        $invoice = $this->invoiceService->createInvoice($validated, $validated['items']);

        if ($request->wantsJson()) {
            return response()->json($invoice, 201);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function show($id)
    {
        $invoice = \App\Models\Invoice::with(['customer', 'shipment', 'items'])->findOrFail($id);
        return response()->json($invoice);
    }

    public function finalize($id)
    {
        $invoice = $this->invoiceService->finalizeInvoice($id);
        return response()->json($invoice);
    }

    public function downloadPdf($id)
    {
        $invoice = \App\Models\Invoice::with(['customer', 'shipment', 'items'])->findOrFail($id);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        $filename = str_replace(['/', '\\'], '-', $invoice->invoice_number);
        
        return $pdf->download('Invoice-' . $filename . '.pdf');
    }
}
