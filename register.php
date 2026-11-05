<?php
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Серверлік тексеру (Валидация)
    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "Барлық өрістерді толтыру міндетті!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Электронды пошта форматы қате!";
    } elseif (strlen($password) < 6) {
        $error = "Құпиясөз кемінде 6 символдан тұруы керек!";
    } else {
        $success = "Сәтті тіркелдіңіз! Енді жүйеге кіре аласыз.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="form-container">
    <div class="form-box">
        <h2>Жүйеге тіркелу</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; margin-bottom: 15px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color: green; margin-bottom: 15px;"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST" id="registerForm">
            <div class="input-group">
                <label for="fullname">Аты-жөніңіз:</label>
                <input type="text" id="fullname" name="fullname" required placeholder="Диана Ахмет">
            </div>
            <div class="input-group">
                <label for="email">Электронды пошта:</label>
                <input type="email" id="email" name="email" required placeholder="student@university.kz">
            </div>
            <div class="input-group">
                <label for="password">Құпиясөз:</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn">Тіркелу</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>