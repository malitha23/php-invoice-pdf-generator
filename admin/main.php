<style>
    /* General Page Styles */


    h1 {
        text-align: center;
        color: #333;
        margin-top: 20px;
    }

    h3 {
        color: #555;
    }

    .container {
        width: 80%;
        margin: 20px auto;
        background-color: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Form Styles */
    form {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        margin: 4px 0 4px;
        font-size: 14px;
    }

    input[type="text"],
    input[type="date"],
    input[type="email"],
    input[type="number"] {
        padding: 10px;
        margin-bottom: 0px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="email"]:focus,
    input[type="number"]:focus {
        border-color: #4CAF50;
    }

    input[type="submit"] {
        padding: 12px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Section Spacing */
    .form-section {
        margin-bottom: 4px;
    }

    .form-section h3 {
        margin-top: 20px;
        margin-bottom: 15px;
    }

    .form-section input {
        width: 100%;
    }

    /* Input Group Styles */
    .input-group {
        display: flex;
        flex-direction: column;
    }

    .input-group input {
        width: 100%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        h1 {
            font-size: 24px;
        }

        input[type="submit"] {
            font-size: 14px;
            padding: 10px 15px;
        }
    }
</style>

<h1>Edit Invoice</h1>
<div class="container">
    <form id="invoiceForm" action="process_invoice.php" method="POST">
        <!-- Invoice Number -->
        <label for="invoice_number">Invoice Number:</label>
        <input type="text" id="invoice_number" name="invoice_number" required><br><br>

        <!-- Invoice Date -->
        <label for="invoice_date">Invoice Date:</label>
        <input type="date" id="invoice_date" name="invoice_date" required><br><br>

        <!-- Invoice Date -->
        <label for="invoice_currency">Invoice Currency:</label>
        <input type="text" id="invoice_currency" name="invoice_currency" required><br><br>

        <!-- Invoice Date -->
        <label for="invoice_tax">Invoice Tax(%):</label>
        <input type="number" id="invoice_tax" name="invoice_tax" required><br><br>

        <!-- Invoice To Section -->
        <h3>Invoice To</h3>
        <label for="name">Name:</label>
        <input type="text" id="name" name="invoice_to[name]" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="invoice_to[address]" required><br><br>

        <label for="country">Country:</label>
        <input type="text" id="country" name="invoice_to[country]" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="invoice_to[email]" required><br><br>

        <!-- Payment Method -->
        <label for="payment_method">Payment Method:</label>
        <input type="text" id="payment_method" name="payment_method" required><br><br>

        <!-- Submit Button -->
        <input type="submit" value="Save Invoice">
    </form>
</div>
<script>
    window.onload = loadInvoices;

    // Function to load invoices
    function loadInvoices() {
        fetch('itemsManage.php', { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                console.log(data);  // Check the structure of the response

                // If the response is an object, wrap it in an array
                if (!Array.isArray(data)) {
                    data = [data];  // Convert single object to array
                }

                // Now process the data as an array
                if (data.length > 0) {
                    // Assuming we're working with the first invoice (you can adjust logic for multiple invoices)
                    const invoice = data[0];

                    // Populate the form fields with data from the invoice
                    document.getElementById('invoice_number').value = invoice.invoice_number;
                    document.getElementById('invoice_date').value = invoice.invoice_date;
                    document.getElementById('invoice_tax').value = invoice.tax;
                    document.getElementById('invoice_currency').value = invoice.currencyFormat;

                    // Invoice To
                    document.getElementById('name').value = invoice.invoice_to.name;
                    document.getElementById('address').value = invoice.invoice_to.address;
                    document.getElementById('country').value = invoice.invoice_to.country;
                    document.getElementById('email').value = invoice.invoice_to.email;

                    // Payment Method
                    document.getElementById('payment_method').value = invoice.payment_method;
                }
            })
            .catch(error => {
                console.error('Error fetching invoice data:', error);
            });
    }

    // Optional: Add an event listener to handle form submission and send the updated data back
    document.getElementById('invoiceForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting normally

        // Create the updated invoice object
        const updatedInvoice = {
            invoice_number: document.getElementById('invoice_number').value,
            invoice_date: document.getElementById('invoice_date').value,
            invoice_tax: document.getElementById('invoice_tax').value,
            invoice_currency: document.getElementById('invoice_currency').value,
            invoice_to: {
                name: document.getElementById('name').value,
                address: document.getElementById('address').value,
                country: document.getElementById('country').value,
                email: document.getElementById('email').value
            },
            payment_method: document.getElementById('payment_method').value
        };

        // Send the updated data back to the server
        fetch('mainDataManage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedInvoice)
        })
            .then(response => response.json())
            .then(data => {
                console.log('Invoice updated successfully:', data);
                alert('Invoice updated successfully!');
            })
            .catch(error => {
                console.error('Error updating invoice:', error);
                alert('Failed to update invoice.');
            });
    });
</script>