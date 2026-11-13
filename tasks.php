<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// 1. CREATE: Жаңа тапсырма қосу (Пән, Түрі, Басымдылығымен)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $subject = trim($_POST['subject']);
    $title = trim($_POST['title']);
    $task_type = $_POST['task_type'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];

    if (!empty($subject) && !empty($title) && !empty($deadline)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, subject, title, task_type, deadline, priority, status) VALUES (?, ?, ?, ?, ?, ?, 'Орындалуда')");
        if ($stmt->execute([$user_id, $subject, $title, $task_type, $deadline, $priority])) {
            $message = "Тапсырма сәтті қосылды!";
        } else {
            $message = "Қате орын алды.";
        }
    } else {
        $message = "Міндетті өрістерді толтырыңыз!";
    }
}

// 2. DELETE: Жою
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php");
    exit();
}

// 3. UPDATE STATUS: Статусты өзгерту
if (isset($_GET['complete'])) {
    $task_id = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'Аяқталды' WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    header("Location: tasks.php");
    exit();
}

// Іздеу, Сүзу және Сұрыптау
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';
$status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'deadline_asc';

$sql = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];

if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR subject LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($type_filter)) {
    $sql .= " AND task_type = ?";
    $params[] = $type_filter;
}

if (!empty($status_filter)) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

if ($sort_by === 'deadline_asc') {
    $sql .= " ORDER BY deadline ASC";
} elseif ($sort_by === 'deadline_desc') {
    $sql .= " ORDER BY deadline DESC";
} else {
    $sql .= " ORDER BY created_at DESC";
}

// Пагинация
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$count_sql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_tasks = $stmt_count->fetchColumn();
$total_pages = ceil($total_tasks / $limit);

$sql .= " LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>UniHelper - Smart Planner & Tasks</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .task-container { max-width: 1050px; margin: 30px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .task-form, .filter-form { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; background: #f9f9f9; padding: 15px; border-radius: 6px; }
        .task-form input, .task-form select, .filter-form input, .filter-form select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 140px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #1a237e; color: white; }
        .badge-type { background: #e3f2fd; color: #1565c0; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px; }
        .badge-priority-жоғары { background: #ffebee; color: #c62828; padding: 3px 6px; border-radius: 4px; font-size: 12px; }
        .badge-priority-орташа { background: #fff8e1; color: #f57f17; padding: 3px 6px; border-radius: 4px; font-size: 12px; }
        .badge-priority-төмен { background: #e8f5e9; color: #2e7d32; padding: 3px 6px; border-radius: 4px; font-size: 12px; }
        .btn-del { background-color: #e74c3c; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; }
        .btn-done { background-color: #27ae60; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; margin-right: 5px; }
        .pagination { display: flex; gap: 5px; margin-top: 20px; justify-content: center; }
        .pagination a { padding: 8px 12px; border: 1px solid #ddd; background: #fff; color: #1a237e; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #1a237e; color: white; border-color: #1a237e; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">UniHelper</div>
        <nav>
            <a href="dashboard.php">Жеке кабинет</a>
            <a href="tasks.php" class="active">Тапсырмалар (Planner)</a>
            <a href="logout.php" style="color: #ff8a80;">Шығу</a>
        </nav>
    </header>

    <main>
        <div class="task-container">
            <h2 style="color: #1a237e; margin-bottom: 20px;">📊 Smart Academic Planner & Tasks</h2>

            <?php if (!empty($message)): ?>
                <p style="color: #27ae60; margin-bottom: 15px; font-weight: bold;"><?= $message ?></p>
            <?php endif; ?>

            <!-- CREATE формасы: Пән, Тапсырма, Түрі, Дедлайн, Басымдық -->
            <form action="tasks.php" method="POST" class="task-form">
                <input type="text" name="subject" required placeholder="Пән атауы (Мысалы: Java)">
                <input type="text" name="title" required placeholder="Тапсырма тақырыбы">
                <select name="task_type" required>
                    <option value="Дәріс">Дәріс</option>
                    <option value="Практика">Практика</option>
                    <option value="СӨЖ">СӨЖ</option>
                    <option value="Жоба">Жоба</option>
                    <option value="Зертханалық жұмыс">Зертханалық жұмыс</option>
                </select>
                <input type="date" name="deadline" required>
                <select name="priority">
                    <option value="Жоғары">Жоғары</option>
                    <option value="Орташа" selected>Орташа</option>
                    <option value="Төмен">Төмен</option>
                </select>
                <button type="submit" name="add_task" style="background: #1a237e; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">Қосу</button>
            </form>

            <!-- Іздеу және Сүзу панелі -->
            <form method="GET" action="tasks.php" class="filter-form">
                <input type="text" name="search" placeholder="Іздеу (пән не тапсырма)..." value="<?= htmlspecialchars($search) ?>">
                
                <select name="type_filter">
                    <option value="">Барлық түрлері</option>
                    <option value="Дәріс" <?= $type_filter === 'Дәріс' ? 'selected' : '' ?>>Дәріс</option>
                    <option value="Практика" <?= $type_filter === 'Практика' ? 'selected' : '' ?>>Практика</option>
                    <option value="СӨЖ" <?= $type_filter === 'СӨЖ' ? 'selected' : '' ?>>СӨЖ</option>
                    <option value="Жоба" <?= $type_filter === 'Жоба' ? 'selected' : '' ?>>Жоба</option>
                    <option value="Зертханалық жұмыс" <?= $type_filter === 'Зертханалық жұмыс' ? 'selected' : '' ?>>Зертханалық жұмыс</option>
                </select>

                <select name="status_filter">
                    <option value="">Барлық статустар</option>
                    <option value="Орындалуда" <?= $status_filter === 'Орындалуда' ? 'selected' : '' ?>>Орындалуда</option>
                    <option value="Аяқталды" <?= $status_filter === 'Аяқталды' ? 'selected' : '' ?>>Аяқталды</option>
                </select>

                <button type="submit" style="background: #3f51b5; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">Сүзу</button>
                <a href="tasks.php" style="background: #757575; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; text-align: center;">Тазарту</a>
            </form>

            <!-- READ Кестесі -->
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Пән</th>
                        <th>Тапсырма</th>
                        <th>Түрі</th>
                        <th>Дедлайн</th>
                        <th>Басымдығы</th>
                        <th>Статус</th>
                        <th>Әрекеттер</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($tasks) > 0): ?>
                        <?php foreach ($tasks as $index => $task): ?>
                            <tr>
                                <td><?= $offset + $index + 1 ?></td>
                                <td><strong><?= htmlspecialchars($task['subject']) ?></strong></td>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><span class="badge-type"><?= htmlspecialchars($task['task_type']) ?></span></td>
                                <td><?= $task['deadline'] ?></td>
                                <td><span class="badge-priority-<?= mb_strtolower($task['priority']) ?>"><?= $task['priority'] ?></span></td>
                                <td>
                                    <span style="color: <?= $task['status'] === 'Аяқталды' ? '#27ae60' : '#e67e22' ?>; font-weight: bold;">
                                        <?= $task['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($task['status'] !== 'Аяқталды'): ?>
                                        <a href="tasks.php?complete=<?= $task['id'] ?>" class="btn-done">Бітті</a>
                                    <?php endif; ?>
                                    <a href="tasks.php?delete=<?= $task['id'] ?>" class="btn-del" onclick="return confirm('Өшіргіңіз келе ме?')">Жою</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #777;">Тапсырмалар табылмады.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Пагинация -->
            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="tasks.php?page=<?= $i ?>&search=<?= urlencode($search) ?>&type_filter=<?= urlencode($type_filter) ?>&status_filter=<?= urlencode($status_filter) ?>" class="<?= $page === $i ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <footer>
        <p>&copy; 2026 UniHelper. Барлық құқықтар қорғалған.</p>
    </footer>
</body>
</html>