// Handles AJAX blog create, list, delete, and client-side validation.
document.addEventListener("DOMContentLoaded", function () {
    const blogForm = document.getElementById("blogForm");
    const refreshBtn = document.getElementById("refreshBtn");

    loadBlogs();

    refreshBtn.addEventListener("click", function () {
        loadBlogs();
    });

    blogForm.addEventListener("submit", function (event) {
        event.preventDefault();

        clearFormErrors();

        const title = document.getElementById("title").value.trim();
        const content = document.getElementById("content").value.trim();

        let isValid = true;

        if (title === "") {
            showError("titleError", "Blog title is required.");
            isValid = false;
        }

        if (content === "") {
            showError("contentError", "Blog content is required.");
            isValid = false;
        }

        if (content !== "" && content.length < 10) {
            showError("contentError", "Blog content must be at least 10 characters.");
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        const formData = new FormData();
        formData.append("title", title);
        formData.append("content", content);

        fetch("/online-car-rent/api/blog_api.php?action=create", {
            method: "POST",
            body: formData
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            const formMessage = document.getElementById("formMessage");
            formMessage.textContent = result.message;

            if (result.success) {
                blogForm.reset();
                loadBlogs();
            }
        })
        .catch(function () {
            document.getElementById("formMessage").textContent = "Request failed. Please try again.";
        });
    });
});

function loadBlogs() {
    const blogList = document.getElementById("blogList");

    blogList.innerHTML = "<p>Loading blogs...</p>";

    fetch("/online-car-rent/api/blog_api.php?action=list")
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            blogList.innerHTML = "";

            if (!result.success) {
                blogList.innerHTML = "<p class='error-box'>" + result.message + "</p>";
                return;
            }

            if (result.data.length === 0) {
                blogList.innerHTML = "<p class='empty-text'>No blog post found.</p>";
                return;
            }

            result.data.forEach(function (blog) {
                const card = document.createElement("article");
                card.className = "blog-card";

                const title = document.createElement("h3");
                title.innerHTML = blog.title;

                const meta = document.createElement("p");
                meta.className = "blog-meta";
                meta.innerHTML = "Posted by " + blog.author + " on " + blog.created_at;

                const content = document.createElement("p");
                content.className = "blog-content";
                content.innerHTML = blog.content;

                card.appendChild(title);
                card.appendChild(meta);
                card.appendChild(content);

                if (blog.can_delete) {
                    const deleteBtn = document.createElement("button");
                    deleteBtn.className = "delete-btn";
                    deleteBtn.textContent = "Delete";

                    deleteBtn.addEventListener("click", function () {
                        deleteBlog(blog.id);
                    });

                    card.appendChild(deleteBtn);
                }

                blogList.appendChild(card);
            });
        })
        .catch(function () {
            blogList.innerHTML = "<p class='error-box'>Could not load blog posts.</p>";
        });
}

function deleteBlog(blogId) {
    const confirmDelete = confirm("Are you sure you want to delete this blog?");

    if (!confirmDelete) {
        return;
    }

    const formData = new FormData();
    formData.append("id", blogId);

    fetch("/online-car-rent/api/blog_api.php?action=delete", {
        method: "POST",
        body: formData
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (result) {
        alert(result.message);

        if (result.success) {
            loadBlogs();
        }
    })
    .catch(function () {
        alert("Delete request failed.");
    });
}

function showError(elementId, message) {
    document.getElementById(elementId).textContent = message;
}

function clearFormErrors() {
    document.getElementById("titleError").textContent = "";
    document.getElementById("contentError").textContent = "";
    document.getElementById("formMessage").textContent = "";
}