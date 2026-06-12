<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    public function index(Request $request)
    {
        $shipments = $this->shipmentService->getAllShipments($request->all());
        
        if ($request->wantsJson()) {
            return response()->json($shipments);
        }

        $customers = \App\Models\Customer::where('status', 'active')->get();

        return view('shipments.index', compact('shipments', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_number' => 'required|unique:shipments',
            'customer_id' => 'required|exists:customers,id',
            'container_type' => 'nullable',
            'vessel_name' => 'nullable',
            'pol' => 'nullable',
            'pod' => 'nullable',
            'etd' => 'nullable|date',
            'eta' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        $shipment = $this->shipmentService->createShipment($validated);

        if ($request->wantsJson()) {
            return response()->json($shipment, 201);
        }

        return redirect()->route('shipments.index')->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function show($id)
    {
        $shipment = \App\Models\Shipment::with(['customer', 'histories', 'documents'])->findOrFail($id);
        return response()->json($shipment);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $shipment = $this->shipmentService->updateStatus($id, $validated['status'], $validated['description']);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($shipment);
        }

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
