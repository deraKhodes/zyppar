<?php
session_start();
require __DIR__ . '/config.php';
if (!empty($_SESSION['admin_logged_in'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $password = (string)($_POST['password'] ?? '');
    if (hash_equals(ADMIN_PASSWORD, $password)) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php'); exit;
    }
    $error = 'That password did not match. Try again.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sign in · Pinpoint</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><div class="auth-glow"></div><main class="auth-card"><a class="brand" href="login.php"><span class="brand-mark">P</span><span>pinpoint<span class="brand-dot">.</span><small>LOCATION LINKS</small></span></a><div class="auth-icon">⌁</div><p class="eyebrow">PRIVATE WORKSPACE</p><h1>Welcome back.</h1><p class="muted">Sign in to manage your location links and reports.</p>
<?php if ($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label for="password">Admin password</label><input id="password" type="password" name="password" placeholder="Enter your password" required autofocus><button class="btn primary full" type="submit">Sign in <span>→</span></button></form><div class="auth-foot"><span class="status-dot"></span> Local workspace <span class="foot-right">v1.0</span></div></main><p class="auth-note">Location is only reported when a visitor knowingly grants browser permission.</p></body></html>
