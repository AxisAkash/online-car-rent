document.addEventListener("DOMContentLoaded", function () {
    const orderForm = document.getElementById("orderForm");

    if (!orderForm) {
        return;
    }

    const startDateInput = document.getElementById("startDate");
    const endDateInput = document.getElementById("endDate");
    const totalDaysText = document.getElementById("totalDays");
    const totalCostText = document.getElementById("totalCost");
    const errorText = document.getElementById("orderFormError");

    const pricePerDay = parseFloat(orderForm.dataset.price);

    function showError(message) {
        errorText.textContent = message;
    }

    function clearError() {
        errorText.textContent = "";
    }

    function calculateTotal() {
        const startDateValue = startDateInput.value;
        const endDateValue = endDateInput.value;

        totalDaysText.textContent = "0 day";
        totalCostText.textContent = "BDT 0.00";

        if (!startDateValue || !endDateValue) {
            return false;
        }

        const startDate = new Date(startDateValue);
        const endDate = new Date(endDateValue);
        const today = new Date();

        today.setHours(0, 0, 0, 0);

        if (startDate < today) {
            showError("Start date cannot be in the past.");
            return false;
        }

        if (endDate <= startDate) {
            showError("End date must be after start date.");
            return false;
        }

        const differenceInTime = endDate.getTime() - startDate.getTime();
        const totalDays = differenceInTime / (1000 * 60 * 60 * 24);
        const totalCost = totalDays * pricePerDay;

        totalDaysText.textContent = totalDays + (totalDays === 1 ? " day" : " days");
        totalCostText.textContent = "BDT " + totalCost.toFixed(2);

        clearError();
        return true;
    }

    startDateInput.addEventListener("change", calculateTotal);
    endDateInput.addEventListener("change", calculateTotal);

    orderForm.addEventListener("submit", function (event) {
        const isValid = calculateTotal();

        if (!isValid) {
            event.preventDefault();
        }
    });
});