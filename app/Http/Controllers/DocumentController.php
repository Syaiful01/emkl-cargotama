<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request)
    {
        $documents = \App\Models\Document::with(['shipment', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($documents);
        }

        $shipments = \App\Models\Shipment::orderBy('job_number', 'asc')->get();

        return view('documents.index', compact('documents', 'shipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'type' => 'required|string',
            'title' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $document = $this->documentService->uploadDocument($validated, $request->file('file'));

        if ($request->wantsJson()) {
            return response()->json($document, 201);
        }

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,png|max:10240',
        ]);

        $document = $this->documentService->updateDocument($id, $request->file('file'));
        return response()->json($document);
    }

    public function show($id)
    {
        $document = \App\Models\Document::with(['shipment', 'versions', 'user'])->findOrFail($id);
        
        if (request()->wantsJson()) {
            return response()->json($document);
        }

        return view('documents.show', compact('document'));
    }

    public function preview($id)
    {
        $document = \App\Models\Document::findOrFail($id);
        return Storage::response($document->file_path);
    }

    public function parse($id)
    {
        $data = $this->documentService->parseWithAI($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $this->documentService->deleteDocument($id);
        return response()->json(['message' => 'Document deleted successfully']);
    }
}
