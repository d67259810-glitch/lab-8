<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniHelper - Оқу материалдары</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>UniHelper Dashboard</h1>
            <nav>
                <a href="index.html">Басты бет</a>
                <a href="dashboard.html" class="active">Материалдар</a>
                <a href="login.html" class="btn-logout">Шығу</a>
            </nav>
        </div>
    </header>

    <main class="container dashboard-main">
        <aside class="sidebar">
            <h3>Санаттар</h3>
            <ul>
                <li><a href="#">Барлық пәндер</a></li>
                <li><a href="#">Бағдарламалау негіздері</a></li>
                <li><a href="#">Дерекқорлар</a></li>
                <li><a href="#">Веб әзірлеу</a></li>
            </ul>
        </aside>

        <section class="content">
            <h2>Оқу материалдары мен тапсырмалар</h2>
            <div class="search-bar">
                <input type="text" placeholder="Материалдарды іздеу...">
                <button class="btn-primary">Іздеу</button>
            </div>

            <div class="card-grid">
                <div class="card">
                    <h3>PHP және MySQL негіздері</h3>
                    <p>Бұл материалда PHP тілінің кіріспесі мен дерекқорға қосылу жолдары көрсетілген.</p>
                    <span class="tag">Веб әзірлеу</span>
                </div>
                <div class="card">
                    <h3>Дерекқор жобалау (ER-диаграмма)</h3>
                    <p>MySQL кестелерін дұрыс құру және байланыстыру ережелері.</p>
                    <span class="tag">Дерекқорлар</span>
                </div>
            </div>
        </section>
    </main>
</body>
</html>