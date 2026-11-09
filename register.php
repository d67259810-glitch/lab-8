<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $message = "Бұл пошта бұрын тіркелген!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($insert->execute([$username, $email, $hashedPassword])) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $message = "Қате орын алды.";
            }
        }
    } else {
        $message = "Барлық өрістерді толтырыңыз!";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Тіркелу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Жоғарғы меню -->
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="index.php">Басты бет</a>
            <a href="courses.php">Курстар</a>
            <a href="login.php">Кіру</a>
            <a href="register.php" class="active">Тіркелу</a>
        </nav>
    </header>

    <!-- Негізгі контент -->
    <main>
        <div class="form-container">
            <div class="form-box">
                <h2>Жүйеге тіркелу</h2>

                <?php if (!empty($message)): ?>
                    <p style="color: #e74c3c; font-size: 14px; text-align: center; margin-bottom: 15px;"><?= $message ?></p>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="input-group">
                        <label>Пайдаланушы аты</label>
                        <input type="text" name="username" required placeholder="Атыңыз">
                    </div>
                    <div class="input-group">
                        <label>Электронды пошта</label>
                        <input type="email" name="email" required placeholder="name@example.com">
                    </div>
                    <div class="input-group">
                        <label>Құпия сөз</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Тіркелу</button>
                </form>

                <p style="margin-top: 20px; text-align: center; font-size: 14px;">
                    Аккаунтыңыз бар ма? <a href="login.php" style="color: #3f51b5; text-decoration: none;">Кіру</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Төменгі футер -->
    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>