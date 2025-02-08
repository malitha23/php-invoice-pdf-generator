<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>
    <style>
        .invoice-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .invoice-item h3 {
            margin: 0;
            font-size: 18px;
        }
        .invoice-item ul {
            list-style-type: none;
            padding-left: 0;
        }
        .invoice-item ul li {
            margin-bottom: 10px;
        }
        .invoice-item button {
            margin-left: 10px;
        }
    </style>
    
</head>
<body>

<h1>Invoice Management</h1>

<div id="invoice-list"></div>

<!-- Add Item Form -->
<div id="add-item-form" style="display: none;">
    <h3>Add Item to Invoice</h3>
    <label>Description: </label><input type="text" id="item-description"><br>
    <label>Details: </label><input type="text" id="item-details"><br>
    <label>Price: </label><input type="number" id="item-unit-price"><br>
    <button onclick="addItem()">Add Item</button>
    <button onclick="cancelAddItem()">Cancel</button>
</div>

<!-- Update Item Form -->
<div id="update-item-form" style="display: none;">
    <h3>Update Item</h3>
    <label>Description: </label><input type="text" id="update-item-description"><br>
    <label>Details: </label><input type="text" id="update-item-details"><br>
    <label>Price: </label><input type="number" id="update-item-unit-price"><br>
    <button onclick="updateItem()">Update Item</button>
    <button onclick="cancelUpdateItem()">Cancel</button>
</div>

<script>
// Load invoices on page load
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
            const invoiceList = document.getElementById('invoice-list');
            invoiceList.innerHTML = ''; // Clear current list
            data.forEach(invoice => {
                let invoiceItem = `
                    <div class="invoice-item" id="invoice-${invoice.invoice_id}">
                        <h3>Invoice #${invoice.invoice_id} - Total: $${invoice.total_amount}</h3>
                        <ul>
                            ${invoice.items.map((item, index) => `
                                <li>
                                    <strong>${item.description}</strong>: ${item.details} - $${item.total_price}
                                    <button onclick="deleteItem(${invoice.invoice_id}, ${index})">Delete</button>
                                    <button onclick="showUpdateItemForm(${invoice.invoice_id}, ${index}, '${item.description}', '${item.details}', ${item.total_price})">Update</button>
                                </li>
                            `).join('')}
                        </ul>
                        <button onclick="showAddItemForm(${invoice.invoice_id})">Add Item</button>
                    </div>
                `;
                invoiceList.innerHTML += invoiceItem;
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

// Show Add Item Form
function showAddItemForm(invoiceId) {
    const addItemForm = document.getElementById('add-item-form');
    addItemForm.style.display = 'block';
    addItemForm.setAttribute('data-invoice-id', invoiceId);
}

// Add new item to the invoice
function addItem() {
    const invoiceId = document.getElementById('add-item-form').getAttribute('data-invoice-id');
    const description = document.getElementById('item-description').value;
    const details = document.getElementById('item-details').value;
    const unitPrice = document.getElementById('item-unit-price').value;

    const newItem = {
        description: description,
        details: details,  // Use the details field
        unit_price: parseFloat(unitPrice),
        quantity: 1,
        total_price: parseFloat(unitPrice)
    };

    fetch(`itemsManage.php?invoice_id=${invoiceId}&action=add-item`, {
        method: 'POST',
        body: JSON.stringify(newItem),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadInvoices();
        } else {
            alert(data.message);
        }
    });

    cancelAddItem();
}

// Cancel Add Item
function cancelAddItem() {
    document.getElementById('add-item-form').style.display = 'none';
}

// Show Update Item Form
function showUpdateItemForm(invoiceId, itemIndex, description, details, totalPrice) {
    const updateForm = document.getElementById('update-item-form');
    updateForm.style.display = 'block';
    updateForm.setAttribute('data-invoice-id', invoiceId);
    updateForm.setAttribute('data-item-index', itemIndex);
    document.getElementById('update-item-description').value = description;
    document.getElementById('update-item-details').value = details;  // Add details field
    document.getElementById('update-item-unit-price').value = totalPrice / 2; // Example for default price
}

// Update item
function updateItem() {
    const invoiceId = document.getElementById('update-item-form').getAttribute('data-invoice-id');
    const itemIndex = document.getElementById('update-item-form').getAttribute('data-item-index');
    const description = document.getElementById('update-item-description').value;
    const details = document.getElementById('update-item-details').value;  // Get the updated details
    const unitPrice = document.getElementById('update-item-unit-price').value;


    const updatedItem = {
        description: description,
        details: details,  // Use updated details
        unit_price: parseFloat(unitPrice),
        total_price: parseFloat(unitPrice)
    };

    fetch(`itemsManage.php?invoice_id=${invoiceId}&action=update-item&item_index=${itemIndex}`, {
        method: 'PUT',
        body: JSON.stringify(updatedItem),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadInvoices();
        } else {
            alert(data.message);
        }
    });

    cancelUpdateItem();
}

// Cancel Update Item
function cancelUpdateItem() {
    document.getElementById('update-item-form').style.display = 'none';
}

// Delete an item
function deleteItem(invoiceId, itemIndex) {
    fetch(`itemsManage.php?invoice_id=${invoiceId}&action=delete-item&item_index=${itemIndex}`, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            loadInvoices();
        } else {
            alert(data.message);
        }
    });
}
</script>

</body>
</html>
