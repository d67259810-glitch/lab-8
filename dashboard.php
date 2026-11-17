<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Қолданушы ақпаратын алу
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Статистика (KPI) есептеу
// 1. Барлық тапсырмалар саны
$stmt_tasks = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ?");
$stmt_tasks->execute([$user_id]);
$total_tasks = $stmt_tasks->fetchColumn();

// 2. Аяқталған тапсырмалар саны
$stmt_done = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'Аяқталды'");
$stmt_done->execute([$user_id]);
$done_tasks = $stmt_done->fetchColumn();

// 3. Жазылған курстар саны
$stmt_courses = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE user_id = ?");
$stmt_courses->execute([$user_id]);
$total_courses = $stmt_courses->fetchColumn();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Жеке кабинет (Dashboard)</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container { max-width: 1000px; margin: 30px auto; padding: 20px; }
        .welcome-card { background: linear-gradient(135deg, #1a237e, #3949ab); color: white; padding: 30px; border-radius: 12px; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .welcome-avatar { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border-top: 4px solid #1a237e; }
        .stat-card h3 { font-size: 28px; color: #1a237e; margin-bottom: 5px; }
        .stat-card p { color: #666; font-size: 14px; }
        .quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .link-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-decoration: none; color: inherit; display: block; transition: transform 0.2s; border-left: 5px solid #3f51b5; }
        .link-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        .link-card h4 { color: #1a237e; margin-bottom: 8px; font-size: 18px; }
        .link-card p { color: #555; font-size: 13px; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="dashboard.php" class="active">Жеке кабинет</a>
            <a href="tasks.php">Тапсырмалар</a>
            <a href="courses.php">Курстар</a>
            <a href="profile.php">Профиль</a>
            <a href="logout.php" style="color: #ff8a80;">Шығу</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <!-- Қош келдіңіз блогы -->
        <div class="welcome-card">
            <?php 
                $avatarPath = (!empty($user['avatar']) && file_exists('uploads/' . $user['avatar'])) 
                    ? 'uploads/' . $user['avatar'] 
                    : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; 
            ?>
            <img src="<?= $avatarPath ?>" alt="Avatar" class="welcome-avatar">
            <div>
                <h2>Қош келдіңіз, <?= htmlspecialchars($user['username']) ?>! 👋</h2>
                <p style="margin-top: 5px; opacity: 0.9;">UniHelper аналитикалық жүйесі арқылы оқу үрдісіңізді басқарыңыз.</p>
            </div>
        </div>

        <!-- Аналитикалық KPI көрсеткіштері -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= $total_tasks ?></h3>
                <p>Барлық тапсырмалар</p>
            </div>
            <div class="stat-card" style="border-top-color: #27ae60;">
                <h3 style="color: #27ae60;"><?= $done_tasks ?></h3>
                <p>Орындалған тапсырмалар</p>
            </div>
            <div class="stat-card" style="border-top-color: #f39c12;">
                <h3 style="color: #f39c12;"><?= $total_courses ?></h3>
                <p>Жазылған курстар</p>
            </div>
        </div>

        <!-- Жылдам өту сілтемелері -->
        <div class="quick-links">
            <a href="tasks.php" class="link-card">
                <h4>📋 Тапсырмалар менеджері</h4>
                <p>Үй жұмыстарын, СӨЖ және жобаларды қадағалаңыз, іздеңіз және сүзіңіз.</p>
            </a>
            <a href="courses.php" class="link-card" style="border-left-color: #2e7d32;">
                <h4>📚 Оқу курстары</h4>
                <p>Веб-программирование, Дерекқор базалары және Жасанды интеллект курстарына жазылыңыз.</p>
            </a>
            <a href="profile.php" class="link-card" style="border-left-color: #e67e22;">
                <h4>👤 Жеке профиль</h4>
                <p>Аты-жөніңізді өзгертіп, жеке аватар суретіңізді жүктеңіз.</p>
            </a>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>