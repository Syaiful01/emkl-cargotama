<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'shipment_id', 'customer_id', 'invoice_number', 'invoice_date', 'due_date',
        'subtotal', 'tax', 'grand_total', 'status', 'user_id'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receivable()
    {
        return $this->hasOne(Receivable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
