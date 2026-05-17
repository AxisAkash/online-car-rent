<?php
session_start();

$userName = $_SESSION["name"] ?? null;
$userRole = $_SESSION["role"] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Car Rent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #2563eb);
            color: #111827;
        }

        .navbar {
            width: 100%;
            padding: 20px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .nav-badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 9px 14px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 14px;
        }

        .hero {
    width: 100%;
    min-height: calc(100vh - 120px);
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 20px;
}

        .hero-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 24px;
    padding: 42px;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
    max-width: 650px;
    width: 100%;
}

        .tag {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        h1 {
            font-size: 46px;
            line-height: 1.1;
            margin: 0 0 16px;
            color: #0f172a;
        }

        .subtitle {
            font-size: 17px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 26px;
        }

        .info-box {
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            color: #1e3a8a;
            font-weight: 600;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        a {
            text-decoration: none;
        }

        .btn {
            padding: 13px 18px;
            border-radius: 12px;
            font-weight: 800;
            display: inline-block;
            transition: 0.2s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        .btn-dark:hover {
            background: #020617;
            transform: translateY(-2px);
        }

        .btn-light {
            background: #e0e7ff;
            color: #1e3a8a;
        }

        .btn-light:hover {
            background: #c7d2fe;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        .side-card {
            background: rgba(255, 255, 255, 0.16);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
        }

        .side-card h2 {
            margin-top: 0;
            font-size: 26px;
        }

        
        .footer-note {
            width: 90%;
            max-width: 1100px;
            margin: 20px auto 35px;
            color: rgba(255, 255, 255, 0.85);
            text-align: center;
            font-size: 14px;
        }

        @media (max-width: 850px) {
            .hero {
    min-height: auto;
    padding: 25px 16px;
}

            h1 {
                font-size: 36px;
            }

            .hero-card {
                padding: 30px;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">Online Car Rent</div>
    <div class="nav-badge"> Blog Page Module</div>
</header>

<main class="hero">
    <section class="hero-card">
        <span class="tag">Web Application </span>

        <h1>Share Your Car Rental Experience</h1>

        <p class="subtitle">
            This module allows Admin and Member users to post blog experiences,
            view all blog posts, and delete posts based on role permission.
        </p>

        <?php if ($userName): ?>
            <div class="info-box">
                Logged in as
                <strong><?php echo htmlspecialchars($userName); ?></strong>
                (<?php echo htmlspecialchars($userRole); ?>)
            </div>
        <?php endif; ?>

        <div class="button-group">
            <a class="btn btn-primary" href="/online-car-rent/loginMember.php">Login as Member</a>
            <a class="btn btn-dark" href="/online-car-rent/loginAdmin.php">Login as Admin</a>
            <a class="btn btn-light" href="/online-car-rent/views/blog/blog_page.php">Go to Blog Page</a>

            <?php if ($userName): ?>
                <a class="btn btn-danger" href="/online-car-rent/logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </section>

</main>


<footer class="footer">
    <p>
        <strong>Online Car Rent</strong> &copy; 2026. All rights reserved.
    </p>

</footer>

</body>
</html>

</body>
</html>