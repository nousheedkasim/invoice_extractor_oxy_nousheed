<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

/**
 * Service class for Optical Character Recognition (OCR).
 */
class OcrService
{
    /**
     * The file path to the Tesseract executable.
     * @var string
     */
    protected $tesseractPath;

    /**
     * OcrService constructor.
     * * Pulls the executable path from config/services.php,
     * which in turn looks at the TESSERACT_PATH in your .env file.
     */
    public function __construct()
    {
        $this->tesseractPath = config('services.tesseract.path');
    }

    /**
     * Performs an OCR scan on a given file.
     * * @param string $imagePath
     * @return string
     */
    public function scan($imagePath)
    {
        $ocr = new TesseractOCR($imagePath);

        // Only apply the executable path if one is explicitly provided
        if ($this->tesseractPath) {
            $ocr->executable($this->tesseractPath);
        }

        return $ocr->lang('eng')->run();
    }
}