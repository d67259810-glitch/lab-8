<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Қолданушы деректерін алу
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Профильді жаңарту және файл жүктеу логикасы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    // Сурет жүктеуді тексеру
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Рұқсат етілген кеңейтімдер
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            // Максималды көлемі: 2MB
            if ($fileSize < 2 * 1024 * 1024) {
                // Мұнда time() функциясының жақшасы түзеліп отыр
                $newFileName = md5(time() . $user_id) . '.' . $fileExtension;
                $uploadFileDir = 'uploads/';
                
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Базаға жаңа аватар жолын жазу
                    $updateStmt = $pdo->prepare("UPDATE users SET username = ?, avatar = ? WHERE id = ?");
                    $updateStmt->execute([$username, $newFileName, $user_id]);
                    $message = "Профиль сәтті жаңартылды!";
                    
                    // Мәліметтерді жаңартып оқу
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $message = "Файлды жүктеу кезінде қате шықты.";
                }
            } else {
                $message = "Файл көлемі 2MB-тан аспауы тиіс!";
            }
        } else {
            $message = "Тек jpg, jpeg, png форматындағы суреттер рұқсат етілген!";
        }
    } else {
        // Тек аты-жөнін өзгерту
        $updateStmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $updateStmt->execute([$username, $user_id]);
        $message = "Аты-жөніз сәтті өзгертілді!";
        $user['username'] = $username;
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Жеке Профиль</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center; }
        .avatar-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #1a237e; margin-bottom: 15px; }
        .profile-form { display: flex; flex-direction: column; gap: 15px; text-align: left; margin-top: 20px; }
        .profile-form label { font-weight: bold; color: #333; }
        .profile-form input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-save { background: #1a237e; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; }
        .btn-save:hover { background: #283593; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="dashboard.php">Жеке кабинет</a>
            <a href="tasks.php">Тапсырмалар</a>
            <a href="courses.php">Курстар</a>
            <a href="profile.php" class="active">Профиль</a>
            <a href="logout.php" style="color: #ff8a80;">Шығу</a>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2 style="color: #1a237e; margin-bottom: 20px;">👤 Менің профилім</h2>

            <?php if (!empty($message)): ?>
                <p style="color: #27ae60; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <?php 
                $avatarPath = (!empty($user['avatar']) && file_exists('uploads/' . $user['avatar'])) 
                    ? 'uploads/' . $user['avatar'] 
                    : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; 
            ?>
            <img src="<?= $avatarPath ?>" alt="Avatar" class="avatar-img">

            <form action="profile.php" method="POST" enctype="multipart/form-data" class="profile-form">
                <label>Аты-жөні:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

                <label>Электрондық пошта (өзгертілмейді):</label>
                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: #f5f5f5;">

                <label>Профиль суретін жүктеу (Аватар):</label>
                <input type="file" name="avatar" accept=".jpg, .jpeg, .png, .gif">

                <button type="submit" class="btn-save">Сақтау</button>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>