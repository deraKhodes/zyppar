<?php
session_start();
require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'message'=>'Method not allowed.']); exit; }
$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) { http_response_code(400); echo json_encode(['ok'=>false,'message'=>'Invalid request.']); exit; }
$token = (string)($data['token'] ?? '');
$lat = filter_var($data['latitude'] ?? null, FILTER_VALIDATE_FLOAT);
$lng = filter_var($data['longitude'] ?? null, FILTER_VALIDATE_FLOAT);
$accuracy = filter_var($data['accuracy'] ?? null, FILTER_VALIDATE_FLOAT);
if ($lat === false || $lng === false || $lat === null || $lng === null || $lat < -90 || $lat > 90 || $lng < -180 || $lng > 180 || ($accuracy !== false && $accuracy !== null && ($accuracy < 0 || $accuracy > 10000000))) {
    http_response_code(422); echo json_encode(['ok'=>false,'message'=>'Location coordinates were invalid.']); exit;
}
try {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id,is_active FROM tracking_links WHERE token=? LIMIT 1');
    $stmt->execute([$token]); $link = $stmt->fetch();
    if (!$link || !(int)$link['is_active']) { http_response_code(404); echo json_encode(['ok'=>false,'message'=>'This link is unavailable.']); exit; }
    $stmt = $pdo->prepare('INSERT INTO location_reports (link_id,latitude,longitude,accuracy_meters) VALUES (?,?,?,?)');
    $stmt->execute([(int)$link['id'], round($lat,7), round($lng,7), ($accuracy === false || $accuracy === null) ? null : round($accuracy,2)]);
    echo json_encode(['ok'=>true,'message'=>"Thanks, you're all set."]);
} catch (Throwable $e) {
    http_response_code(500); echo json_encode(['ok'=>false,'message'=>'Could not save the report. Please try again later.']);
}
