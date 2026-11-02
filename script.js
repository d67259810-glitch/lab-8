/**
 * UniHelper - Студенттерге арналған оқу платформасы
 * 9-апта: Пайдаланушы интерфейсінің скрипті (Frontend Logic)
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('UniHelper интерфейс скрипті сәтті іске қосылды.');

    // ----------------------------------------------------
    // 1. Кіру және Тіркелу формаларын өзара ауыстыру
    // ----------------------------------------------------
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginBtn && registerBtn && loginForm && registerForm) {
        // "Кіру" батырмасын басқанда
        loginBtn.addEventListener('click', () => {
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
            loginBtn.classList.add('active');
            registerBtn.classList.remove('active');
        });

        // "Тіркелу" батырмасын басқанда
        registerBtn.addEventListener('click', () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            registerBtn.classList.add('active');
            loginBtn.classList.remove('active');
        });
    }

    // ----------------------------------------------------
    // 2. Формалардың жіберілуін тексеру (Валидация симуляциясы)
    // ----------------------------------------------------
    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Беттің қайта жүктелуін тоқтатамыз
            alert('Жүйеге кіру орындалды! (PHP қосылғанда дерекқордан тексеріледі)');
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            event.preventDefault();
            alert('Тіркелу сәтті аяқталды! (PHP қосылғанда users кестесіне сақталады)');
        });
    }

    // ----------------------------------------------------
    // 3. Материалдарды іздеу функциясы (Dashboard бетінде)
    // ----------------------------------------------------
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');

    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', () => {
            const query = searchInput.value.trim();
            if (query !== "") {
                alert(`"${query}" бойынша іздеу орындалуда...`);
            } else {
                alert('Іздеу үшін сөз енгізіңіз!');
            }
        });
    }
});