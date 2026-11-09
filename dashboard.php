<?php
session_start();

// Егер қолданушы жүйеге кірмеген болса, қайтадан login.php-ге лақтыру
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Жеке кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Жоғарғы меню -->
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="index.php">Басты бет</a>
            <a href="courses.php">Курстар</a>
            <a href="logout.php" style="color: #ff8a80;">Шығу</a>
        </nav>
    </header>

    <!-- Негізгі контент -->
    <main>
        <div class="container" style="text-align: center; padding: 60px 20px;">
            <h1 style="color: #1a237e; margin-bottom: 20px;">Қош келдіңіз, <?= htmlspecialchars($_SESSION['username']); ?>! 🎉</h1>
            <p style="font-size: 18px; color: #555; margin-bottom: 30px;">Бұл — сіздің UniHelper платформасындағы жеке кабинетіңіз.</p>
            
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); max-width: 500px; margin: 0 auto;">
                <h3>Сіздің деректеріңіз:</h3>
                <p style="margin-top: 10px;"><b>ID:</b> <?= $_SESSION['user_id']; ?></p>
                <p style="margin-top: 5px;"><b>Пайдаланушы аты:</b> <?= htmlspecialchars($_SESSION['username']); ?></p>
            </div>
        </div>
    </main>

    <!-- Төменгі футер -->
    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>