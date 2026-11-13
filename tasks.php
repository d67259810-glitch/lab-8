<?php
session_start();
require_once 'db.php';

// Егер қолданушы жүйеге кірмеген болса, login.php-ге бағыттау
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// 1. CREATE: Жаңа тапсырма қосу
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $deadline = $_POST['deadline'];

    if (!empty($title) && !empty($deadline)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, deadline, status) VALUES (?, ?, ?, 'Орындалуда')");
        if ($stmt->execute([$user_id, $title, $deadline])) {
            $message = "Тапсырма сәтті қосылды!";
        } else {
            $message = "Қате орын алды.";
        }
    } else {
        $message = "Барлық өрістерді толтырыңыз!";
    }
}

// 2. DELETE: Тапсырманы жою
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php");
    exit();
}

// 3. UPDATE STATUS: Статусты өзгерту (Аяқталды ету)
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'Аяқталды' WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php");
    exit();
}

// 4. READ: Қолданушының тапсырмаларын оқу/көрсету
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Тапсырмалар (CRUD)</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .task-container { max-width: 800px; margin: 40px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .task-form { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .task-form input { padding: 10px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 200px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #1a237e; color: white; }
        .btn-del { background-color: #e74c3c; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; }
        .btn-del:hover { background-color: #c0392b; }
        .btn-done { background-color: #27ae60; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; margin-right: 5px; }
        .btn-done:hover { background-color: #219653; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="dashboard.php">Жеке кабинет</a>
            <a href="tasks.php" class="active">Тапсырмалар</a>
            <a href="logout.php" style="color: #ff8a80;">Шығу</a>
        </nav>
    </header>

    <main>
        <div class="task-container">
            <h2 style="color: #1a237e; margin-bottom: 20px;">Тапсырмалар менеджері (Smart Tasks)</h2>

            <?php if (!empty($message)): ?>
                <p style="color: #27ae60; margin-bottom: 15px; font-weight: bold;"><?= $message ?></p>
            <?php endif; ?>

            <!-- CREATE формасы -->
            <form action="tasks.php" method="POST" class="task-form">
                <input type="text" name="title" required placeholder="Тапсырма атауы (Мысалы: Курстық жұмыс)">
                <input type="date" name="deadline" required>
                <button type="submit" name="add_task" class="btn">Қосу</button>
            </form>

            <!-- READ кестесі -->
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Тапсырма</th>
                        <th>Дедлайн</th>
                        <th>Статус</th>
                        <th>Әрекеттер</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($tasks) > 0): ?>
                        <?php foreach ($tasks as $index => $task): ?>
                            <tr>
                                <td style="width: 40px;"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= $task['deadline'] ?></td>
                                <td>
                                    <span style="color: <?= $task['status'] === 'Аяқталды' ? '#27ae60' : '#e67e22' ?>; font-weight: bold;">
                                        <?= $task['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($task['status'] !== 'Аяқталды'): ?>
                                        <a href="tasks.php?complete=<?= $task['id'] ?>" class="btn-done">Бітті</a>
                                    <?php endif; ?>
                                    <a href="tasks.php?delete=<?= $task['id'] ?>" class="btn-del" onclick="return confirm('Шындығында өшіргіңіз келе ме?')">Жою</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #777;">Әзірге тапсырмалар жоқ. Жоғарыдан жаңасын қосыңыз!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>