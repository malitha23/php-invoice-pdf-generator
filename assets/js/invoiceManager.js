document.addEventListener("DOMContentLoaded", loadInvoices);

function reloadIframe() {
  reloadContent();
}

function reloadContent() {
  // var currentUrl = window.location.href;

  // // Add a random query parameter to the URL to bypass the cache
  // var newUrl = currentUrl.split("?")[0] + "?_=" + new Date().getTime();

  // // Reload the page with the new URL
  loadInvoices();
  document.getElementById("myIframe").src =  "../general_3.php" + "?_=" + new Date().getTime();;
}

function loadInvoices() {
  fetch("itemsManage.php", { method: "GET" })
    .then((response) => response.json())
    .then((invoice) => {
      console.log(invoice);

      if (!invoice || typeof invoice !== "object") {
        console.error("Invalid invoice data:", invoice);
        return;
      }

      document.getElementById("invoice_number").value =
        invoice.invoice_number || "";
      document.getElementById("invoice_date").value =
        invoice.invoice_date || "";
      document.getElementById("invoice_tax").value = invoice.tax || "";
      document.getElementById("invoice_currency").value =
        invoice.currencyFormat || "";

      if (invoice.invoice_to) {
        document.getElementById("name").value = invoice.invoice_to.name || "";
        document.getElementById("address").value =
          invoice.invoice_to.address || "";
        document.getElementById("country").value =
          invoice.invoice_to.country || "";
        document.getElementById("email").value = invoice.invoice_to.email || "";
      }

      document.getElementById("payment_method").value =
        invoice.payment_method || "";
    })
    .catch((error) => {
      console.error("Error fetching invoice data:", error);
    });
}

document
  .getElementById("invoiceForm1")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const updatedInvoice = {
      invoice_number: document.getElementById("invoice_number").value,
      invoice_date: document.getElementById("invoice_date").value,
      invoice_tax: document.getElementById("invoice_tax").value,
      invoice_currency: document.getElementById("invoice_currency").value,
      invoice_to: {
        name: document.getElementById("name").value,
        address: document.getElementById("address").value,
        country: document.getElementById("country").value,
        email: document.getElementById("email").value,
      },
      payment_method: document.getElementById("payment_method").value,
    };

    fetch("mainDataManage.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedInvoice),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Invoice updated successfully:", data);
        Swal.fire({
          title: "Updated!",
          text: "Invoice updated successfully.",
          icon: "success",
          timer: 1000,
          showConfirmButton: false,
        }).then(() => {
          reloadIframe();
        });
      })
      .catch((error) => {
        console.error("Error updating invoice:", error);
        Swal.fire("Error!", error, "error");
      });
  });

document
  .getElementById("invoiceForm2")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const updatedInvoice = {
      invoice_number: document.getElementById("invoice_number").value,
      invoice_date: document.getElementById("invoice_date").value,
      invoice_tax: document.getElementById("invoice_tax").value,
      invoice_currency: document.getElementById("invoice_currency").value,
      invoice_to: {
        name: document.getElementById("name").value,
        address: document.getElementById("address").value,
        country: document.getElementById("country").value,
        email: document.getElementById("email").value,
      },
      payment_method: document.getElementById("payment_method").value,
    };

    fetch("mainDataManage.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedInvoice),
    })
      .then((response) => response.json())
      .then((data) => {
        Swal.fire({
          title: "Updated!",
          text: "Invoice updated successfully.",
          icon: "success",
          timer: 1000,
          showConfirmButton: false,
        }).then(() => {
          reloadIframe();
        });
      })
      .catch((error) => {
        console.error("Error updating invoice:", error);
        Swal.fire("Error!", error, "error");
      });
  });
