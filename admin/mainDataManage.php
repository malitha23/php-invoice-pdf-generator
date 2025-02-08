<?php
// Set content type to JSON
header('Content-Type: application/json');

$jsonFilePath = '../assets/invoices.json';

function getInvoiceById($id)
{
    $invoice = readInvoices();

    if ($invoice['invoice_id'] == $id) {
        return $invoice;
    }
    return null;
}

function readInvoices()
{
    global $jsonFilePath;
    $data = file_get_contents($jsonFilePath);
    return json_decode($data, true);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve raw POST data
    $inputData = json_decode(file_get_contents('php://input'), true);

    if ($inputData) {
        // Invoice details from the POST data
        $invoiceNumber = $inputData['invoice_number'];
        $invoiceDate = $inputData['invoice_date'];
        $paymentMethod = $inputData['payment_method'];
        $invoice_tax = $inputData['invoice_tax'];
        $invoice_currency = $inputData['invoice_currency'];

        // Invoice "To" details
        $invoiceToName = $inputData['invoice_to']['name'];
        $invoiceToAddress = $inputData['invoice_to']['address'];
        $invoiceToCountry = $inputData['invoice_to']['country'];
        $invoiceToEmail = $inputData['invoice_to']['email'];

        // Read current invoice data by ID
        $invoice = getInvoiceById(1); // Example for invoice ID = 1

        if ($invoice) {
            // Update invoice data with the new details from POST request
            $invoice['invoice_number'] = $invoiceNumber;
            $invoice['invoice_date'] = $invoiceDate;
            $invoice['payment_method'] = $paymentMethod;
            $invoice['tax'] = $invoice_tax;
            $invoice['currencyFormat'] = $invoice_currency;
            $invoice['invoice_to']['name'] = $invoiceToName;
            $invoice['invoice_to']['address'] = $invoiceToAddress;
            $invoice['invoice_to']['country'] = $invoiceToCountry;
            $invoice['invoice_to']['email'] = $invoiceToEmail;

            // Save the updated data to the file
            file_put_contents('../assets/invoices.json', json_encode($invoice, JSON_PRETTY_PRINT));

            // Response to confirm the update
            echo json_encode(["status" => "success", "message" => "Invoice updated successfully."]);
        } else {
            // Invoice not found
            echo json_encode(["status" => "error", "message" => "Invoice not found."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid data."]);
    }
}