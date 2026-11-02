<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniHelper - Кіру / Тіркелу</title>
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts (Заманауи қаріп) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-body">

    <div class="auth-wrapper">
        <!-- Сол жақ бренд немесе сәлемдесу бөлігі -->
        <div class="auth-left">
            <div class="brand-content">
                <h1>UniHelper</h1>
                <p>Студенттерге арналған оқу платформасы. Білім жолындағы сенімді көмекшіңіз!</p>
                <div class="circles">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <!-- Оң жақ формалар бөлігі -->
        <div class="auth-right">
            <div class="form-toggle-pill">
                <button id="loginBtn" class="pill-btn active">Кіру</button>
                <button id="registerBtn" class="pill-btn">Тіркелу</button>
            </div>

            <!-- Кіру формасы -->
            <form id="loginForm" class="auth-form">
                <h2>Қош келдіңіз! 👋</h2>
                <p class="form-subtitle">Жүйеге кіру үшін деректеріңізді енгізіңіз</p>
                
                <div class="input-group">
                    <label>Электронды пошта</label>
                    <input type="email" required placeholder="name@example.com">
                </div>
                <div class="input-group">
                    <label>Құпия сөз</label>
                    <input type="password" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn-submit">Жүйеге кіру</button>
            </form>

            <!-- Тіркелу формасы -->
            <form id="registerForm" class="auth-form" style="display: none;">
                <h2>Тіркелу 🚀</h2>
                <p class="form-subtitle">Жаңа аккаунт ашып, жобаны бастаңыз</p>

                <div class="input-group">
                    <label>Пайдаланушы аты</label>
                    <input type="text" required placeholder="Атыңыз">
                </div>
                <div class="input-group">
                    <label>Электронды пошта</label>
                    <input type="email" required placeholder="name@example.com">
                </div>
                <div class="input-group">
                    <label>Құпия сөз</label>
                    <input type="password" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn-submit">Тіркелу</button>
            </form>
        </div>
    </div>

    <!-- JavaScript логикасы -->
    <script src="script.js"></script>
</body>
</html>