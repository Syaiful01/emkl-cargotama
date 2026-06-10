<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    public function uploadDocument(array $data, UploadedFile $file)
    {
        $path = $file->store('shipments/' . $data['shipment_id'] . '/documents');

        $document = Document::create([
            'shipment_id' => $data['shipment_id'],
            'type' => $data['type'],
            'title' => $data['title'] ?? $file->getClientOriginalName(),
            'file_path' => $path,
            'version' => 1,
            'user_id' => Auth::id() ?? 1,
        ]);

        DocumentVersion::create([
            'document_id' => $document->id,
            'file_path' => $path,
            'version' => 1,
            'user_id' => Auth::id() ?? 1,
        ]);

        return $document;
    }

    public function updateDocument($id, UploadedFile $file)
    {
        $document = Document::findOrFail($id);
        $newVersion = $document->version + 1;
        
        $path = $file->store('shipments/' . $document->shipment_id . '/documents');

        $document->update([
            'file_path' => $path,
            'version' => $newVersion,
        ]);

        DocumentVersion::create([
            'document_id' => $document->id,
            'file_path' => $path,
            'version' => $newVersion,
            'user_id' => Auth::id() ?? 1,
        ]);

        return $document;
    }

    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete physical files
        foreach ($document->versions as $version) {
            Storage::delete($version->file_path);
        }
        
        return $document->delete();
    }

    public function parseWithAI($documentId)
    {
        $document = Document::findOrFail($documentId);
        
        // Simulating AI parsing logic
        // In a real scenario, this would call an OCR service or LLM
        return [
            'vessel_name' => 'MAERSK SEOUL',
            'voyage' => '623W',
            'container_number' => 'MSKU9283741',
            'weight' => 24500.50,
            'pol' => 'TANJUNG PERAK, SURABAYA',
            'pod' => 'ROTTERDAM, NETHERLANDS',
            'eta' => now()->addDays(20)->format('Y-m-d'),
        ];
    }
}
