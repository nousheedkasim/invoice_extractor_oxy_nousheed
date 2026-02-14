<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

/**
 * Service class for Digital PDF Text Extraction.
 * * This service extracts text layers directly from PDF files.
 * It is significantly faster than OCR and should always be the 
 * first method attempted for PDF documents.
 */
class PdfService
{
    /**
     * The PDF Parser instance.
     * @var Parser
     */
    protected Parser $parser;

    /**
     * PdfService constructor.
     * Initializes the Smalot PDF Parser.
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * Extract raw text from a PDF file.
     * * @param string $filePath The absolute path to the PDF file.
     * @return string The extracted text. 
     * Returns an empty string if the PDF is scanned/image-based.
     * @throws \Exception If the file is corrupted or unreadable.
     */
    public function extractText(string $filePath): string
    {
        // Parse the PDF file into a document object
        $pdf = $this->parser->parseFile($filePath);
        
        // Retrieve and return all text found across all pages
        return $pdf->getText();
    }
}