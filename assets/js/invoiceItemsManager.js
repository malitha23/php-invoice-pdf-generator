// Load invoices on page load
window.onload = loadInvoices;

// Function to load invoices
function loadInvoices() {
  fetch("itemsManage.php", { method: "GET" })
    .then((response) => response.json())
    .then((data) => {
      console.log(data); // Check the structure of the response

      // If the response is an object, wrap it in an array
      if (!Array.isArray(data)) {
        data = [data]; // Convert single object to array
      }

      // Now process the data as an array
      const invoiceList = document.getElementById("invoice-list");
      invoiceList.innerHTML = ""; // Clear current list
      data.forEach((invoice) => {
        let invoiceItem = `
                  <h3><b>Items - Total: $${invoice.total_amount}</b></h3>
                  <hr>
             <div class="invoice-item" id="invoice-${invoice.invoice_id}">
                 <ul>
                     ${invoice.items
                       .map(
                         (item, index) => `
                         <li>
                             <strong>${item.Item}</strong>: ${item.Description} - $${item.total_price}
                             <button onclick="deleteItem(${invoice.invoice_id}, ${index})">Delete</button>
                             <button onclick="showUpdateItemForm(${invoice.invoice_id}, ${index}, '${item.Item}', '${item.Description}', ${item.total_price})">Update</button>
                         </li>
                     `
                       )
                       .join("")}
                 </ul>
                 <button onclick="showAddItemForm(${
                   invoice.invoice_id
                 })">Add Item</button>
             </div>
         `;
        invoiceList.innerHTML += invoiceItem;
      });
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}

// Show Add Item Form
function showAddItemForm(invoiceId) {
  const addItemForm = document.getElementById("add-item-form");
  addItemForm.style.display = "block";
  addItemForm.setAttribute("data-invoice-id", invoiceId);
}

// Add new item to the invoice
function addItem() {
  const invoiceId = document
    .getElementById("add-item-form")
    .getAttribute("data-invoice-id");
  const Item = document.getElementById("item-name").value;
  const Description = document.getElementById("Item-des").value;
  const unitPrice = document.getElementById("item-unit-price").value;

  const newItem = {
    Item: Item,
    Description: Description, // Use the Description field
    unit_price: parseFloat(unitPrice),
    quantity: 1,
    total_price: parseFloat(unitPrice),
  };

  fetch(`itemsManage.php?invoice_id=${invoiceId}&action=add-item`, {
    method: "POST",
    body: JSON.stringify(newItem),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        Swal.fire({
          title: "Success!",
          text: "Item added successfully.",
          icon: "success",
          timer: 1000, // Auto close after 2 seconds
          showConfirmButton: false, // Hide the "OK" button
        }).then(() => {
          reloadIframe();
        });
      } else {
        Swal.fire("Error!", data.message, "error");
      }
    });

  cancelAddItem();
}

// Cancel Add Item
function cancelAddItem() {
  document.getElementById("add-item-form").style.display = "none";
}

// Show Update Item Form
function showUpdateItemForm(
  invoiceId,
  itemIndex,
  Item,
  Description,
  totalPrice
) {
  const updateForm = document.getElementById("update-item-form");
  updateForm.style.display = "block";
  updateForm.setAttribute("data-invoice-id", invoiceId);
  updateForm.setAttribute("data-item-index", itemIndex);
  document.getElementById("update-item-name").value = Item;
  document.getElementById("update-Item-des").value = Description; // Add Description field
  document.getElementById("update-item-unit-price").value = totalPrice; // Example for default price
}

// Update item
// Update item with confirmation
function updateItem() {
  const invoiceId = document
    .getElementById("update-item-form")
    .getAttribute("data-invoice-id");
  const itemIndex = document
    .getElementById("update-item-form")
    .getAttribute("data-item-index");
  const Item = document.getElementById("update-item-name").value;
  const Description = document.getElementById("update-Item-des").value; // Get the updated Description
  const unitPrice = document.getElementById("update-item-unit-price").value;

  const updatedItem = {
    Item: Item,
    Description: Description, // Use updated Description
    unit_price: parseFloat(unitPrice),
    total_price: parseFloat(unitPrice),
  };

  Swal.fire({
    title: "Are you sure?",
    text: "Do you want to update this item?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes, update it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(
        `itemsManage.php?invoice_id=${invoiceId}&action=update-item&item_index=${itemIndex}`,
        {
          method: "PUT",
          body: JSON.stringify(updatedItem),
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Show success SweetAlert and auto-close after 2 seconds
            Swal.fire({
              title: "Updated!",
              text: "Item has been updated.",
              icon: "success",
              timer: 1000,
              showConfirmButton: false,
            }).then(() => {
              reloadIframe();
            });
          } else {
            Swal.fire("Error!", data.message, "error");
          }
        });
    }
  });
}

// Cancel Update Item
function cancelUpdateItem() {
  document.getElementById("update-item-form").style.display = "none";
}

// Delete an item with confirmation
function deleteItem(invoiceId, itemIndex) {
  Swal.fire({
    title: "Are you sure?",
    text: "This action cannot be undone.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(
        `itemsManage.php?invoice_id=${invoiceId}&action=delete-item&item_index=${itemIndex}`,
        {
          method: "DELETE",
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Show success SweetAlert and auto-close after 2 seconds
            Swal.fire({
              title: "Deleted!",
              text: "Item has been deleted.",
              icon: "success",
              timer: 1000,
              showConfirmButton: false,
            }).then(() => {
              reloadIframe();
            });
          } else {
            Swal.fire("Error!", data.message, "error");
          }
        });
    }
  });
}
