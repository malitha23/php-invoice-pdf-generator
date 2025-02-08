<?php
// Set content type to JSON
header('Content-Type: application/json');

// Path to the JSON file
$jsonFilePath = '../assets/invoices.json';

// Read JSON data
function readInvoices()
{
    global $jsonFilePath;
    $data = file_get_contents($jsonFilePath);
    return json_decode($data, true);
}

// Save data back to the JSON file
function saveInvoices($data)
{
    global $jsonFilePath;
    file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Get invoice by ID
function getInvoiceById($id)
{
    $invoice = readInvoices();

        if ($invoice['invoice_id'] == $id) {
            return $invoice;
        }
    return null;
}

// Add a new item to an invoice
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['invoice_id']) && isset($_GET['action']) && $_GET['action'] === 'add-item') {
    $invoiceId = $_GET['invoice_id'];
    $newItem = json_decode(file_get_contents('php://input'), true);

    // Fetch the invoice by ID
    $invoice = getInvoiceById($invoiceId);
    if ($invoice) {
        // Add new item to the invoice
        $invoice['items'][] = $newItem;

        // Recalculate the total amount of the invoice
        $totalAmount = 0;
        foreach ($invoice['items'] as $item) {
            $totalAmount += $item['total_price'];
        }
        $invoice['total_amount'] = $totalAmount;


        // Save the updated invoices array back to the JSON file
        global $jsonFilePath;
        $jsonData = json_encode($invoice, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'JSON encoding error: ' . json_last_error_msg()]);
        } else {
            $writeResult = file_put_contents($jsonFilePath, $jsonData);

            if ($writeResult === false) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to write to file']);
            } else {
                echo json_encode(['status' => 'success', 'invoice' => $invoice]);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invoice not found']);
    }
}

// Update an invoice item
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['invoice_id']) && isset($_GET['action']) && $_GET['action'] === 'update-item') {
    $invoiceId = $_GET['invoice_id'];
    $itemIndex = $_GET['item_index'];
    $updatedItem = json_decode(file_get_contents('php://input'), true);
    $description = $updatedItem['description']; // "App Development"
    $details = $updatedItem['details'];         // "Android & iOS Application Development"
    $total_price = $updatedItem['total_price'];

    $invoice = getInvoiceById($invoiceId);
    // Check if the invoice exists
if ($invoice) {
    // Update the item at the specified index
    if (isset($invoice['items'][$itemIndex])) {
        $invoice['items'][$itemIndex]['description'] = $description;
        $invoice['items'][$itemIndex]['details'] = $details;
        $invoice['items'][$itemIndex]['total_price'] = $total_price;

        // Recalculate the total amount
        $totalAmount = 0;
        foreach ($invoice['items'] as $item) {
            $totalAmount += $item['total_price'];
        }

        // Update the total amount in the invoice
        $invoice['total_amount'] = $totalAmount;

        // Save the updated invoice (you can use a function like saveInvoice())
        saveInvoices($invoice);

        // Respond with the updated invoice data
        echo json_encode(['status' => 'success', 'invoice' => $invoice]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invoice not found']);
}
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['invoice_id']) && isset($_GET['action']) && $_GET['action'] === 'delete-item') {
    $invoiceId = $_GET['invoice_id'];       // Get the invoice ID from the query string
    $itemIndex = $_GET['item_index'];       // Get the item index to delete

    $invoice = getInvoiceById($invoiceId);  // Retrieve the invoice by ID

    // Ensure $invoice is an array and contains 'items'
    if (is_array($invoice) && isset($invoice['items']) && is_array($invoice['items'])) {
        // Check if the item index is valid
        if (isset($invoice['items'][$itemIndex])) {
            // Debugging: output the item before deletion
            echo 'Item to delete: ';
            var_dump($invoice['items'][$itemIndex]);

            // Remove the item at the given index
            array_splice($invoice['items'], $itemIndex, 1);

            // Debugging: output the items after deletion
            echo 'Items after deletion: ';
            var_dump($invoice['items']);

            // Recalculate the total amount
            $totalAmount = 0;
            foreach ($invoice['items'] as $item) {
                $totalAmount += $item['total_price'];
            }
            $invoice['total_amount'] = $totalAmount;

            // Save the updated invoice data
            saveInvoices($invoice);  // Save the modified invoices back to storage

            // Return a success response
            echo json_encode(['status' => 'success', 'invoice' => $invoice]);
        } else {
            // Item index is invalid
            echo json_encode(['status' => 'error', 'message' => 'Item index out of bounds']);
        }
    } else {
        // Invoice not found or invalid structure
        echo json_encode(['status' => 'error', 'message' => 'Invalid invoice data']);
    }
}


// Handle other cases (GET request for all invoices)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $invoices = readInvoices();
    echo json_encode($invoices);
}
