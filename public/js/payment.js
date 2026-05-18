document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("paymentForm");

    if (!paymentForm) {
        return;
    }

    const paymentMethod = document.getElementById("paymentMethod");
    const transactionId = document.getElementById("transactionId");
    const transactionLabel = document.getElementById("transactionLabel");
    const paymentHelpText = document.getElementById("paymentHelpText");
    const paymentFormError = document.getElementById("paymentFormError");

    function updateTransactionField() {
        if (paymentMethod.value === "cash_on_delivery") {
            transactionLabel.textContent = "Transaction ID (optional)";
            transactionId.placeholder = "No transaction ID needed for cash on delivery";
            paymentHelpText.textContent = "Cash on Delivery does not require a transaction ID.";
        } else {
            transactionLabel.textContent = "Transaction ID";
            transactionId.placeholder = "Enter transaction ID";
            paymentHelpText.textContent = "For bKash, Nagad, card, or bank transfer, enter a demo transaction ID.";
        }
    }

    paymentMethod.addEventListener("change", updateTransactionField);

    paymentForm.addEventListener("submit", function (event) {
        paymentFormError.textContent = "";

        if (paymentMethod.value === "") {
            event.preventDefault();
            paymentFormError.textContent = "Please select a payment method.";
            return;
        }

        if (paymentMethod.value !== "cash_on_delivery" && transactionId.value.trim() === "") {
            event.preventDefault();
            paymentFormError.textContent = "Transaction ID is required for this payment method.";
        }
    });

    updateTransactionField();
});