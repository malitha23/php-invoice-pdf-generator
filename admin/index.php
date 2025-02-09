<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('X-Frame-Options: SAMEORIGIN'); // Prevents embedding on external sites

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-Equiv="Cache-Control" Content="no-cache" />
    <meta http-Equiv="Pragma" Content="no-cache" />
    <meta http-Equiv="Expires" Content="0" />
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.17/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/css/invoiceItemsManager.css">
    <link rel="stylesheet" href="../assets/css/invoiceManager.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="grid-container">
        <!-- Invoice Form Section -->
        <div class="container">
            <h3><b>Main</b></h3>
            <hr>
            <form id="invoiceForm1">
                <label for="invoice_number">Invoice Number:</label>
                <input type="text" id="invoice_number" name="invoice_number">

                <label for="invoice_date">Invoice Date:</label>
                <input type="date" id="invoice_date" name="invoice_date">

                <label for="invoice_currency">Currency:</label>
                <input type="text" id="invoice_currency" name="invoice_currency">

                <label for="invoice_tax">Tax (%):</label>
                <input type="number" id="invoice_tax" name="invoice_tax">

                <label for="payment_method">Payment Method:</label>
                <input type="text" id="payment_method" name="payment_method">

                <input type="submit" value="Save Invoice">
            </form>
        </div>
        <div class="container">
            <form id="invoiceForm2">
                <h3><b>Invoice To</b></h3>
                <hr>
                <label for="name">Name:</label>
                <input type="text" id="name" name="invoice_to[name]">

                <label for="address">Address:</label>
                <input type="text" id="address" name="invoice_to[address]">

                <label for="country">Country:</label>
                <input type="text" id="country" name="invoice_to[country]">

                <label for="email">Email:</label>
                <input type="email" id="email" name="invoice_to[email]">

                <input type="submit" value="Save Invoice">
            </form>
        </div>
        <div id="content">
            <?php include 'items.php'; ?>
        </div>
    </div>
    <!-- Add Item Form Modal -->
    <div id="add-item-form" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cancelAddItem()">&times;</span>
            <h3>Add Item to Invoice</h3>
            <label>Item: </label><input type="text" id="item-name"><br>
            <label>Description: </label><input type="text" id="Item-des"><br>
            <label>Price: </label><input type="number" id="item-unit-price"><br>
            <button onclick="addItem()">Add Item</button>
            <button onclick="cancelAddItem()">Cancel</button>
        </div>
    </div>

    <!-- Update Item Form Modal -->
    <div id="update-item-form" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cancelUpdateItem()">&times;</span>
            <h3>Update Item</h3>
            <label>Item: </label><input type="text" id="update-item-name"><br>
            <label>Description: </label><input type="text" id="update-Item-des"><br>
            <label>Price: </label><input type="number" id="update-item-unit-price"><br>
            <button onclick="updateItem()">Update Item</button>
            <button onclick="cancelUpdateItem()">Cancel</button>
        </div>
    </div>
    <!-- Iframe Section -->
    <div class="containerIframe">
        <iframe src="../general_3.php" class="responsive-iframe" id="myIframe"></iframe>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/invoiceManager.js"></script>
    <script src="../assets/js/invoiceItemsManager.js"></script>

</body>

</html>