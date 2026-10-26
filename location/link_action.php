<?php
session_start();
require __DIR__ . '/config.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
csrf_check();
$pdo = db();
$action = (string)($_POST['action'] ?? '');
try {
    if ($action === 'create') {
        $label = trim((string)($_POST['label'] ?? ''));
        $notice = trim((string)($_POST['notice'] ?? ''));
        if ($label === '' || mb_strlen($label) > 120 || $notice === '' || mb_strlen($notice) > 255) {
            flash('error', 'Please enter a valid link name and visitor notice.');
        } else {
            $token = bin2hex(random_bytes(16));
            $stmt = $pdo->prepare('INSERT INTO tracking_links (token,label,consent_notice) VALUES (?,?,?)');
            $stmt->execute([$token,$label,$notice]);
            flash('success', 'Your location link is ready. Copy it from the links list.');
        }
    } elseif ($action === 'toggle') {
        $stmt = $pdo->prepare('UPDATE tracking_links SET is_active = IF(is_active=1,0,1) WHERE id=?');
        $stmt->execute([(int)($_POST['id'] ?? 0)]);
        flash('success', 'Link status updated.');
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM tracking_links WHERE id=?');
        $stmt->execute([(int)($_POST['id'] ?? 0)]);
        flash('success', 'Link and associated reports deleted.');
    } else { flash('error', 'Unknown action.'); }
} catch (Throwable $e) { flash('error', 'Could not complete that action. Check your database configuration.'); }
header('Location: dashboard.php'); exit;
