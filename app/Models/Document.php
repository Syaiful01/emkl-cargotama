<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'shipment_id', 'type', 'title', 'file_path', 'version', 'user_id'
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
