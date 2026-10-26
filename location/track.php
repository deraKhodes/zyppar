<?php
session_start();
require __DIR__ . '/config.php';
$token = trim((string)($_GET['t'] ?? ''));
$stmt = db()->prepare('SELECT id,token,label,consent_notice,is_active FROM tracking_links WHERE token=? LIMIT 1');
$stmt->execute([$token]);
$link = $stmt->fetch();
if (!$link || !(int)$link['is_active']) { http_response_code(404); $invalid = true; } else {
    db()->prepare('UPDATE tracking_links SET click_count=click_count+1 WHERE id=?')->execute([(int)$link['id']]);
    $invalid = false;
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $invalid ? 'Link unavailable':'Location sharing · Pinpoint' ?></title><link rel="stylesheet" href="assets/style.css"></head>
<body class="visitor-page"><div class="visitor-top"><a class="brand" href="#"><span class="brand-mark">P</span><span>pinpoint<span class="brand-dot">.</span></span></a><span class="secure-label"><span class="status-dot"></span> Secure location sharing</span></div>
<main class="visitor-card">
<?php if ($invalid): ?><div class="visitor-illustration error-illustration">×</div><p class="eyebrow">LINK UNAVAILABLE</p><h1>This link can't be opened.</h1><p class="muted">It may have expired, been paused, or been entered incorrectly. Contact the person who shared it with you.</p>
<?php else: ?><div class="visitor-illustration"><div class="radar"><span></span><i>⌖</i></div></div><p class="eyebrow">LOCATION REQUEST</p><h1><?=e($link['label'])?></h1><p class="visitor-copy"><?=e($link['consent_notice'])?></p><div class="privacy-note"><span>◈</span><div><b>Your choice, your privacy</b><p>Your browser will ask whether to share your device location with the person who created this link. Choose Allow to send it, or deny the request to decline.</p></div></div><p class="visitor-status" id="location-status" data-token="<?=e($link['token'])?>" aria-live="polite">Waiting for your browser's permission prompt…</p><p class="visitor-fineprint">This page requests location access automatically. It cannot bypass your browser's permission setting.</p>
<?php endif; ?></main><div class="visitor-footer">Powered by Pinpoint <span>•</span> Transparent by design</div><script src="assets/track.js"></script></body></html>
