<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniHelper - Кіру / Тіркелу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

    <div class="auth-container">
        <!-- Формаларды өзара ауыстыратын түймелер -->
        <div class="form-toggle">
            <button id="loginBtn" class="active">Кіру</button>
            <button id="registerBtn">Тіркелу</button>
        </div>

        <!-- Кіру формасы (users кестесіне сай) -->
        <form id="loginForm" class="auth-form">
            <h2>Жүйеге кіру</h2>
            <div class="input-group">
                <label>Электронды почта (Email)</label>
                <input type="email" id="loginEmail" required placeholder="name@example.com">
            </div>
            <div class="input-group">
                <label>Құпия сөз (Password)</label>
                <input type="password" id="loginPassword" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Кіру</button>
        </form>

        <!-- Тіркелу формасы -->
        <form id="registerForm" class="auth-form" style="display: none;">
            <h2>Тіркелу</h2>
            <div class="input-group">
                <label>Пайдаланушы аты (Username)</label>
                <input type="text" id="regUsername" required placeholder="Диана">
            </div>
            <div class="input-group">
                <label>Электронды почта (Email)</label>
                <input type="email" id="regEmail" required placeholder="name@example.com">
            </div>
            <div class="input-group">
                <label>Құпия сөз (Password)</label>
                <input type="password" id="regPassword" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Тіркелу</button>
        </form>
    </div>

    <!-- Бөлек жасалған JavaScript файлын қосу -->
    <script src="script.js"></script>
</body>
</html>