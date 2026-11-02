document.addEventListener("DOMContentLoaded", () => {
    // Курсқа жазылу түймелері
    const buyButtons = document.querySelectorAll(".btn-buy");
    buyButtons.forEach(button => {
        button.addEventListener("click", () => {
            alert("Сіз курсқа сәтті жазылдыңыз! 📚");
        });
    });

    // Кіру формасының тексерілісі
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const email = document.getElementById("email").value;
            alert(`Қош келдіңіз, ${email}! Жүйеге сәтті кірдіңіз.`);
        });
    }
});
