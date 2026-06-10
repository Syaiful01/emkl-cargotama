<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShipmentService
{
    public function getAllShipments($filters = [])
    {
        $query = Shipment::with('customer');

        if (!empty($filters['search'])) {
            $query->where('job_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('shipment_number', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    public function createShipment(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Ensure status is set for history logging
            if (empty($data['status'])) {
                $data['status'] = 'draft';
            }

            $shipment = Shipment::create($data);
            
            $this->logHistory($shipment->id, $shipment->status, 'Shipment created.');
            
            return $shipment;
        });
    }

    public function updateStatus($id, $status, $description = null)
    {
        return DB::transaction(function () use ($id, $status, $description) {
            $shipment = Shipment::findOrFail($id);
            $shipment->update(['status' => $status]);
            
            $this->logHistory($id, $status, $description);
            
            return $shipment;
        });
    }

    protected function logHistory($shipmentId, $status, $description = null)
    {
        ShipmentHistory::create([
            'shipment_id' => $shipmentId,
            'status' => $status,
            'description' => $description,
            'user_id' => Auth::id() ?? 1, // Default to admin for now if no auth
        ]);
    }
}
