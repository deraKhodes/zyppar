<?php
session_start();
require __DIR__ . '/config.php';
header('Location: dashboard.php');
exit;
