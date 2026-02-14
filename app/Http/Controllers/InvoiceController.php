<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfService;
use App\Services\OcrService;
use App\Services\InvoiceParserService;

/**
 * Class InvoiceController
 * Handles the uploading, text extraction, and structured parsing of invoice documents.
 */
class InvoiceController extends Controller
{
    // Service classes for handling specific document tasks
    protected PdfService $pdfService;
    protected OcrService $OcrService;
    protected InvoiceParserService $parserService;
    
    /**
     * Injecting services via Constructor.
     */
    public function __construct(PdfService $pdfService, OcrService $OcrService, InvoiceParserService $parserService)
    {
        $this->pdfService = $pdfService;
        $this->OcrService = $OcrService;
        $this->parserService = $parserService;
    }
    
    /**
     * Handle the initial file upload and raw text extraction.
     * * @param Request $request
     */
    public function upload(Request $request)
    {
        // 1. Validate file type and size (Max 4MB)
        $request->validate([
            'invoice_file' => 'required|mimes:pdf,jpg,jpeg,png|max:4096',
        ], [
            'invoice_file.mimes' => 'Only PDF, JPG, and PNG files are allowed.',
            'invoice_file.max' => 'The file size must be less than 4MB.',
        ]);

        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            
            // 2. Store the file in the 'public/invoices' directory
            $path = $file->store('invoices', 'public');            
            $fullPath = storage_path('app/public/' . $path);
            
            $text = '';
            
            // 3. Extraction Strategy
            if ($file->getClientOriginalExtension() === 'pdf') {
                // Try digital text extraction first
                $text = $this->pdfService->extractText($fullPath);

                // Fallback to Tesseract OCR if the PDF is scanned (contains no digital text)
                if (empty(trim($text))) {
                    $text = $this->OcrService->scan($fullPath);
                }
            } else {
                // Direct OCR for image files (JPG/PNG)
                $text = $this->OcrService->scan($fullPath);
				 if (empty(trim($text))) {
                    $text = $this->OcrService->scan($fullPath);
                }
            }

            // 4. Return results to the preview page
            return view('result', [
                'text' => $text,
                'filePath' => Storage::url($path), // Publicly accessible URL
                'fileName' => $path               // Relative path for the confirm step
            ]);
        }

        return back()->withErrors(['invoice_file' => 'Upload failed.']);
    }

    /**
     * Confirm the extracted text and convert it into structured JSON data.
     * * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request)
    {
        // Retrieve the filename stored during the upload step
        $fileName = $request->input('stored_file');
        $fullPath = storage_path('app/public/' . $fileName);

        if (file_exists($fullPath)) {
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $text = '';

            // Re-extract text 
            if ($extension === 'pdf') {
                $text = $this->pdfService->extractText($fullPath);
                if (empty(trim($text))) {
                     $text = "No data found";
                }
            } else {
                $text = $this->OcrService->scan($fullPath);
				if (empty(trim($text))) {
                    $text = "No data found";
                }
				
            }
            
            // 5. Use Regex Service to convert raw text into structured key-value pairs
            $structuredData = $this->parserService->parse($text);

            return response()->json($structuredData, 200, [], JSON_PRETTY_PRINT);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}