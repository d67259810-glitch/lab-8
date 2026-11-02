<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniHelper - Жүйеге кіру</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">🎓 UniHelper</div>
        <nav>
            <a href="index.php">Басты бет</a>
            <a href="courses.php">Курстар</a>
            <a href="login.php" class="active">Кіру / Тіркелу</a>
        </nav>
    </header>

    <main class="form-container">
        <div class="form-box">
            <h2>Жеке кабинетке кіру</h2>
            <form id="loginForm">
                <div class="input-group">
                    <label for="email">Электронды пошта:</label>
                    <input type="email" id="email" required placeholder="student@university.kz">
                </div>
                <div class="input-group">
                    <label for="password">Құпиясөз:</label>
                    <input type="password" id="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn">Кіру</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper оқу платформасы.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>