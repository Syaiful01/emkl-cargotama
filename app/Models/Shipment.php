<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'job_number', 'shipment_number', 'customer_id', 'container_number', 'container_type',
        'vessel_name', 'voyage', 'pol', 'pod', 'etd', 'eta', 'cargo_type', 'weight', 'volume', 'status'
    ];

    protected $casts = [
        'etd' => 'date',
        'eta' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function histories()
    {
        return $this->hasMany(ShipmentHistory::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
