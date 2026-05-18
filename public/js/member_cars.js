document.addEventListener("DOMContentLoaded", function () {
    const typeFilter = document.getElementById("carTypeFilter");
    const searchInput = document.getElementById("carSearchInput");
    const carCards = document.querySelectorAll(".member-car-card");
    const carCountText = document.getElementById("carCountText");
    const noFilteredCars = document.getElementById("noFilteredCars");

    function filterCars() {
        const selectedType = typeFilter.value.toLowerCase();
        const searchText = searchInput.value.trim().toLowerCase();

        let visibleCount = 0;

        carCards.forEach(function (card) {
            const cardType = card.dataset.type.toLowerCase();
            const cardSearch = card.dataset.search.toLowerCase();

            const typeMatches = selectedType === "all" || cardType === selectedType;
            const searchMatches = cardSearch.includes(searchText);

            if (typeMatches && searchMatches) {
                card.classList.remove("hidden");
                visibleCount++;
            } else {
                card.classList.add("hidden");
            }
        });

        if (carCountText) {
            carCountText.textContent = visibleCount + " car(s) available";
        }

        if (noFilteredCars) {
            if (visibleCount === 0) {
                noFilteredCars.classList.remove("hidden");
            } else {
                noFilteredCars.classList.add("hidden");
            }
        }
    }

    if (typeFilter) {
        typeFilter.addEventListener("change", filterCars);
    }

    if (searchInput) {
        searchInput.addEventListener("input", filterCars);
    }
});