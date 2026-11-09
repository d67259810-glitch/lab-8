<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Электронды пошта немесе құпия сөз қате!";
        }
    } else {
        $error = "Барлық өрістерді толтырыңыз!";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Кіру</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Жоғарғы меню -->
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="index.php">Басты бет</a>
            <a href="courses.php">Курстар</a>
            <a href="login.php" class="active">Кіру</a>
            <a href="register.php">Тіркелу</a>
        </nav>
    </header>

    <!-- Негізгі контент (CSS стиліндегі form-container мен form-box қолданылады) -->
    <main>
        <div class="form-container">
            <div class="form-box">
                <h2>Жүйеге кіру</h2>

                <?php if (!empty($error)): ?>
                    <p style="color: #e74c3c; font-size: 14px; text-align: center; margin-bottom: 15px;"><?= $error ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label>Электронды пошта</label>
                        <input type="email" name="email" required placeholder="name@example.com">
                    </div>
                    <div class="input-group">
                        <label>Құпия сөз</label>
                        <input type="password" name="password" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Кіру</button>
                </form>

                <p style="margin-top: 20px; text-align: center; font-size: 14px;">
                    Аккаунтыңыз жоқ па? <a href="register.php" style="color: #3f51b5; text-decoration: none;">Тіркелу</a>
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