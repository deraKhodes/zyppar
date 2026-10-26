<?php
session_start();
require __DIR__ . '/config.php';
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`','',DB_NAME) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . str_replace('`','',DB_NAME) . "`");
    $pdo->exec("CREATE TABLE IF NOT EXISTS tracking_links (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, token CHAR(32) NOT NULL UNIQUE, label VARCHAR(120) NOT NULL,
      consent_notice VARCHAR(255) NOT NULL DEFAULT 'This page can share your device location only if you choose Allow.',
      created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, is_active TINYINT(1) NOT NULL DEFAULT 1,
      click_count INT UNSIGNED NOT NULL DEFAULT 0
    ) ENGINE=InnoDB");
    $pdo->exec("CREATE TABLE IF NOT EXISTS location_reports (
      id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, link_id BIGINT UNSIGNED NOT NULL,
      latitude DECIMAL(10,7) NOT NULL, longitude DECIMAL(10,7) NOT NULL, accuracy_meters DECIMAL(10,2) NULL,
      reported_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      CONSTRAINT fk_report_link FOREIGN KEY (link_id) REFERENCES tracking_links(id) ON DELETE CASCADE,
      INDEX idx_report_link_time (link_id, reported_at)
    ) ENGINE=InnoDB");
    echo '<!doctype html><meta name="viewport" content="width=device-width"><title>Pinpoint setup</title><body style="font:16px system-ui;max-width:700px;margin:10vh auto;padding:24px;color:#14251f"><h1>Pinpoint setup complete</h1><p>Database and tables are ready.</p><p><b>Admin password:</b> ' . htmlspecialchars(ADMIN_PASSWORD,ENT_QUOTES,'UTF-8') . '</p><p><a href="login.php">Open admin login</a></p><p style="color:#a33">For safety, delete setup.php after this step and change ADMIN_PASSWORD in config.php before using outside localhost.</p></body>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h1>Setup failed</h1><p>Check MySQL is running and the database credentials in config.php are correct.</p><pre>' . htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8') . '</pre>';
}
