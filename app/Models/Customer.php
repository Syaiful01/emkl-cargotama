<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'code', 'name', 'pic', 'phone', 'email', 'address', 'npwp', 'notes', 'status'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
