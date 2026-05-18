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

    const carIdInput = orderForm.querySelector('input[name="car_id"]');
    const calculateUrl = orderForm.dataset.calculateUrl;

    let latestCalculationValid = false;

    function showError(message) {
        errorText.textContent = message;
        latestCalculationValid = false;
    }

    function clearError() {
        errorText.textContent = "";
    }

    function resetCostBox() {
        totalDaysText.textContent = "0 day";
        totalCostText.textContent = "BDT 0.00";
    }

    function validateDatesBeforeAjax() {
        const startDateValue = startDateInput.value;
        const endDateValue = endDateInput.value;

        resetCostBox();

        if (!startDateValue || !endDateValue) {
            latestCalculationValid = false;
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

        clearError();
        return true;
    }

    async function calculateTotalByAjax() {
        if (!validateDatesBeforeAjax()) {
            return false;
        }

        const formData = new FormData();
        formData.append("car_id", carIdInput.value);
        formData.append("start_date", startDateInput.value);
        formData.append("end_date", endDateInput.value);

        try {
            const response = await fetch(calculateUrl, {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (!data.success) {
                showError(data.message || "Total cost could not be calculated.");
                return false;
            }

            totalDaysText.textContent = data.days + (Number(data.days) === 1 ? " day" : " days");
            totalCostText.textContent = data.formatted_total;

            clearError();
            latestCalculationValid = true;
            return true;
        } catch (error) {
            showError("Server error. Please try again.");
            return false;
        }
    }

    startDateInput.addEventListener("change", calculateTotalByAjax);
    endDateInput.addEventListener("change", calculateTotalByAjax);

    orderForm.addEventListener("submit", function (event) {
        if (!latestCalculationValid) {
            event.preventDefault();
            calculateTotalByAjax();
        }
    });
});