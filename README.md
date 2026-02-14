
# Oxy Invoice Extractor

**Interview Task for OxyFin**  
Completed by **Nousheed**

A Laravel application that reads messy invoice data from images (JPG, PNG, etc.) or PDF files using OCR, then organizes the extracted information into clean, structured **JSON** format.

## Purpose

Automate invoice data extraction from scanned documents, photos of paper invoices, or digital PDFs — especially when the layout is inconsistent or low quality.

## Features

- Upload single image or PDF
- OCR text recognition via **Tesseract**
- Rule-based + pattern parsing of common invoice fields
- Structured JSON output (vendor, date, invoice number, items, totals, taxes, etc.)
- Simple browser-based interface for testing

## Requirements

- PHP 8.1+
- Composer
- Laravel 10 / 11 (or compatible version used in the project)
- **Tesseract OCR** installed on your machine/server
- Web server (Apache/Nginx) or PHP's built-in server
- Recommended local stack: XAMPP / Laragon / Laravel Valet / Docker

## Installation Steps

Follow these steps carefully to get the project running.




steps
======

### 1. Clone the Repository
	git clone https://github.com/nousheedkasim/invoice_extractor_oxy_nousheed oxy-invoice-extractor
	cd oxy-invoice-extractor

### 2. Install PHP Dependencies
	composer install --prefer-dist --no-progress --optimize-autoloader

### 3. Install & Configure Tesseract OCR
	install "Tesseract" in your machine or server, reffer the "Tesseract Ocr Windows Xampp Laravel.docx" word file along with this project for instruction
	
###	4. Set Up Environment File
	# Tesseract executable path (very important!)
	open .env file located in project root
	update TESSERACT_PATH="" with your installed Tesseract OCR path
		(by default "C:/Program Files/Tesseract-OCR/tesseract.exe")
	
### 5. (Optional) Storage Link
	php artisan storage:link
	
### 6. Run the Application
	http://localhost/oxy-invoice-extractor/public/
	# or
	http://localhost:8080/oxy_invoice_extract/public/
	#(adjust path/port according to your setup)
	
	
	
	
	
	
# Basic Usage

	1- Open the web interface in your browser
	2- Upload an invoice image or PDF file
	3- Wait for processing
	4- View/download the resulting structured JSON


# Troubleshooting Checklist

	* "Tesseract not found" → verify TESSERACT_PATH in .env points to the executable (tesseract.exe on Windows)
	* Command tesseract not recognized → add Tesseract folder to system PATH and restart terminal/server
	* PDFs fail → some OCR setups need Ghostscript or poppler-utils (see package README if using a 3rd-party OCR wrapper)
	* Memory / timeout errors → increase memory_limit and max_execution_time in php.ini
	* Blank JSON / poor extraction → quality of input image matters a lot (try higher resolution, better contrast)
