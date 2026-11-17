<?php
session_start();
require_once 'db.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;
$message = '';

// Курсқа жазылу (POST сұранысы арқылы)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_course'])) {
    if (!$is_logged_in) {
        header("Location: login.php");
        exit();
    }

    $course_id = (int)$_POST['course_id'];

    // Бұрын жазылған-жазылмағанын тексеру
    $check = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $check->execute([$user_id, $course_id]);

    if ($check->rowCount() > 0) {
        $message = "Сіз бұл курсқа бұрын жазылып қойғансыз!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $course_id])) {
            $message = "Сіз курсқа сәтті жазылдыңыз!";
        }
    }
}

// Курстан шығу (Бас тарту)
if (isset($_GET['unenroll']) && $is_logged_in) {
    $course_id = (int)$_GET['unenroll'];
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    header("Location: courses.php");
    exit();
}

// Барлық курстарды базадан алу
$courses = $pdo->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);

// Қолданушы жазылған курстардың ID тізімін алу
$enrolled_ids = [];
if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $enrolled_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Курстар</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .courses-wrapper { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 20px; }
        .course-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; flex-direction: column; justify-content: space-between; }
        .course-card h3 { color: #1a237e; margin-bottom: 10px; font-size: 20px; }
        .course-card p { color: #555; font-size: 14px; line-height: 1.5; margin-bottom: 20px; }
        .btn-enroll { width: 100%; background: #283593; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-size: 15px; font-weight: bold; }
        .btn-enroll:hover { background: #1a237e; }
        .btn-enrolled { width: 100%; background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; padding: 10px; border-radius: 6px; text-align: center; font-weight: bold; font-size: 14px; display: block; text-decoration: none; }
        .cancel-link { display: block; text-align: center; margin-top: 8px; font-size: 12px; color: #e53935; text-decoration: none; }
        .alert-box { background: #e3f2fd; color: #0d47a1; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <?php if ($is_logged_in): ?>
                <a href="dashboard.php">Жеке кабинет</a>
                <a href="tasks.php">Тапсырмалар</a>
                <a href="courses.php" class="active">Курстар</a>
                <a href="logout.php" style="color: #ff8a80;">Шығу</a>
            <?php else: ?>
                <a href="index.php">Басты бет</a>
                <a href="courses.php" class="active">Курстар</a>
                <a href="login.php">Кіру</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="courses-wrapper">
        <h2 style="color: #1a237e; text-align: center;">Қолжетімді оқу курстары</h2>

        <?php if (!empty($message)): ?>
            <div class="alert-box"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div>
                        <h3><?= htmlspecialchars($course['title']) ?></h3>
                        <p><?= htmlspecialchars($course['description']) ?></p>
                    </div>
                    
                    <div>
                        <?php if ($is_logged_in && in_array($course['id'], $enrolled_ids)): ?>
                            <span class="btn-enrolled">✓ Сіз жазылғансыз</span>
                            <a href="courses.php?unenroll=<?= $course['id'] ?>" class="cancel-link" onclick="return confirm('Курстан бас тартқыңыз келе ме?')">Жазылудан бас тарту</a>
                        <?php else: ?>
                            <form method="POST" action="courses.php">
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <button type="submit" name="enroll_course" class="btn-enroll">
                                    <?= $is_logged_in ? 'Курсқа жазылу' : 'Жазылу үшін кіріңіз' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>