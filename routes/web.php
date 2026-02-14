<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. 
|
*/

/**
 * Landing Page
 * Displays the initial file upload form for the Invoice Extractor.
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Invoice Management Routes
 * Handled by the InvoiceController.
 */
Route::prefix('invoice')->group(function () {
    
    /**
     * Step 1: Upload and Initial Scan
     * Receives the file, stores it, and performs initial OCR/Text extraction.
     * @see InvoiceController::upload
     */
    Route::post('/upload', [InvoiceController::class, 'upload'])->name('invoice.upload');

    /**
     * Step 2: Confirmation and Parsing
     * Triggered after the user read the raw data. 
     * Uses InvoiceParserService to structure data via Regex.
     * @see InvoiceController::confirm
     */
    Route::post('/confirm', [InvoiceController::class, 'confirm'])->name('invoice.confirm');
    
});