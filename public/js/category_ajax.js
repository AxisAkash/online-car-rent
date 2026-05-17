// public/js/category_ajax.js

document.addEventListener('DOMContentLoaded', function () {
    const categoryLinks = document.querySelectorAll('.category-link[data-ajax="true"]');
    const ajaxTitle = document.getElementById('ajaxResultTitle');
    const carContainer = document.getElementById('ajaxCarContainer');

    if (!categoryLinks.length || !ajaxTitle || !carContainer) {
        return;
    }

    categoryLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();

            const type = this.dataset.type;

            ajaxTitle.textContent = 'Loading ' + type + ' cars...';
            carContainer.innerHTML = '';

            fetch(BASE_URL + 'api/get_cars_by_category.php?type=' + encodeURIComponent(type))
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        ajaxTitle.textContent = data.message;
                        return;
                    }

                    ajaxTitle.textContent = 'Cars in ' + data.type;

                    if (data.cars.length === 0) {
                        carContainer.innerHTML = '<div class="empty-box">No cars found in this category.</div>';
                        return;
                    }

                    carContainer.innerHTML = data.cars.map(car => createCarCard(car)).join('');
                })
                .catch(() => {
                    ajaxTitle.textContent = 'Something went wrong while loading cars.';
                });
        });
    });
});

function createCarCard(car) {
    const imagePart = car.image_path
        ? `<img src="${BASE_URL}${escapeHtml(car.image_path)}" alt="${escapeHtml(car.name)}">`
        : `<div class="car-placeholder">No Image</div>`;

    return `
        <div class="car-card">
            ${imagePart}
            <div class="car-card-body">
                <h3>${escapeHtml(car.name)}</h3>
                <p><strong>Model:</strong> ${escapeHtml(car.model)}</p>
                <p><strong>Type:</strong> ${escapeHtml(car.type)}</p>
                <p><strong>Price:</strong> BDT ${escapeHtml(car.price_per_day)} / day</p>
                <a class="btn small-btn" href="${BASE_URL}car_details.php?id=${encodeURIComponent(car.id)}">
                    View Details
                </a>
            </div>
        </div>
    `;
}

// Prevent XSS while rendering AJAX data
function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}