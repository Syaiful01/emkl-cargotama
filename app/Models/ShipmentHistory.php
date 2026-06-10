<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'shipment_id', 'status', 'description', 'user_id', 'created_at'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
