<?php
require_once 'config.php';
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = db()->prepare('SELECT id, name, password FROM admins WHERE username = ?');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($_POST['password'] ?? '', $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Incorrect username or password.';
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login | PlaceTrack</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-page">
    <div class="login-card">
        <div class="brand-mark">P</div>
        <p class="eyebrow">PLACEMENT MANAGEMENT</p>
        <h1>Welcome back</h1>
        <p class="muted">Sign in to manage campus placements.</p>
        <?php if ($error): ?>
            <div class="alert danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post"><input type="hidden" name="csrf_token"
                value="<?= e(csrf_token()) ?>"><label>Username</label><input name="username" required
                autocomplete="username" placeholder="Enter username"><label>Password</label><input type="password"
                name="password" required autocomplete="current-password" placeholder="Enter password"><button
                class="btn primary full" type="submit">Sign in</button></form>
        <div class="demo-box"><strong>Demo login</strong><br>Username: <code>admin</code><br>Password:
            <code>admin123</code></div>
    </div>
</body>

</html>