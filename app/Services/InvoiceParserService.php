<?php

namespace App\Services;

class InvoiceParserService
{
    public function parse(string $text)
    {
        $invoices = [];
        
        // 1. Split the messy text into separate invoice blocks
        // This regex looks for "INV_" as a starting point for a new invoice
        $blocks = preg_split('/(?=INV_\d+)/', $text, -1, PREG_SPLIT_NO_EMPTY);
		
		

        foreach ($blocks as $block) {
            $data = [];

            // 2. Extract Invoice ID: Matches INV_ followed by numbers and letters
            preg_match('/INV_[A-Z0-9_]+/', $block, $idMatches);
            $data['invoice_id'] = $idMatches[0] ?? 'N/A';

            // 3. Extract Email
            preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $block, $emailMatches);
            $data['customer_email'] = $emailMatches[0] ?? 'N/A';

            // 4. Extract Currency (Looks for 3 capital letters like USD, GBP, EUR)
            preg_match('/\b(USD|GBP|EUR|AUD|CAD)\b/', $block, $currencyMatches);
            $data['currency'] = $currencyMatches[0] ?? 'USD';

            // 5. Extract Items and Prices
			$data['items'] = [];
			$totalCalculated = 0;

			/**
			 * Regex Breakdown:
			 * /^[\s\d\.\-\*\+\>\)]* -> Ignore leading bullets/numbers: 1., -, *, +, >, )
			 * ([A-Za-z\s\(\)]+?)     -> Capture Group 1: The Description (allow text, spaces, parens)
			 * \s*(?:\-|:|\-\-)\s* -> Ignore separators: - or : or --
			 * [^\d\s]* -> Ignore currency symbols: $, £, €, AUD
			 * (\d+\.\d{2})           -> Capture Group 2: The Price (00.00)
			 */
			$itemPattern = '/^[\s\d\.\-\*\+\>\)]*([A-Za-z\s\(\)]+?)\s*(?:\-|:|\-\-)\s*[^\d\s]*(\d+\.\d{2})/m';

			if (preg_match_all($itemPattern, $block, $itemMatches, PREG_SET_ORDER)) {
				foreach ($itemMatches as $match) {
					
					// 1. Clean the captured price string (remove commas if any)
					//$cleanPrice = str_replace(',', '', $match[2]);
					$formattedPrice = number_format((float)$match[2], 2, '.', '');
					
					// 2. Convert to float and force 2 decimal precision
					//$price = (float)number_format((float)$cleanPrice, 2, '.', '');
		
					$data['items'][] = [
						'description' => trim($match[1]),
						'price' => $formattedPrice //$price
					];
					//$totalCalculated += $price;
					$totalCalculated += (float)$formattedPrice;
				}
			}

            // 6. Extract Total Amount (or use calculated)
            preg_match('/(?:Total|Amount)[:\s]*([\d,]+\.\d{2})/', $block, $totalMatches);
            $data['total_amount'] = isset($totalMatches[1]) ? (float)str_replace(',', '', $totalMatches[1]) : number_format($totalCalculated, 2, '.', '');;

            if ($data['invoice_id'] !== 'N/A') {
                $invoices[] = $data;
            }
        }
		// Check if blocks were even found
        if (empty($invoices)) {
            return 'No valid invoice patterns (starting with INV_) were detected in the provided text.';
        }else{

			return $invoices;
		}
    }
}