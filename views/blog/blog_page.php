<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"])) {
    header("Location: /online-car-rent/index.php");
    exit;
}

$userName = $_SESSION["name"] ?? "User";
$userRole = $_SESSION["role"] ?? "member";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Page | Online Car Rent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/online-car-rent/public/css/blog_style.css">
</head>
<body>

<header class="topbar">
    <div class="brand">Online Car Rent</div>

    <nav>
        <a href="/online-car-rent/index.php">Home</a>
        <a href="/online-car-rent/views/blog/blog_page.php">Blogs</a>
        <a href="/online-car-rent/logout.php">Logout</a>
    </nav>
</header>

<main class="page-wrapper">

    <section class="intro-card">
        <h1>Customer Rental Experiences</h1>
        <p>Share your car rental experience and read feedback from other users.</p>

        <div class="login-info">
            Logged in as <strong><?php echo htmlspecialchars($userName); ?></strong>
            (<?php echo htmlspecialchars($userRole); ?>)
        </div>
    </section>

    <section class="form-card">
        <h2>Post a Blog</h2>

        <form id="blogForm" novalidate>
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" placeholder="Example: My first rental experience">
                <small class="error-text" id="titleError"></small>
            </div>

            <div class="form-group">
                <label for="content">Experience</label>
                <textarea id="content" name="content" placeholder="Write your rental experience here"></textarea>
                <small class="error-text" id="contentError"></small>
            </div>

            <button type="submit" class="primary-btn">Post Blog</button>
        </form>

        <p id="formMessage" class="message"></p>
    </section>

    <section class="blog-section">
        <div class="section-title-row">
            <h2>All Blog Posts</h2>
            <button type="button" class="refresh-btn" id="refreshBtn">Refresh</button>
        </div>

        <div id="blogList" class="blog-list">
            <p>Loading blogs...</p>
        </div>
    </section>

</main>

<script src="/online-car-rent/public/js/blog_script.js"></script>
</body>
</html>