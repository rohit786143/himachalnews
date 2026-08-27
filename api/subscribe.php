<?php
/**
 * Device-Based 1-Click Subscription API Endpoint
 * News 24 Himachal
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

$response = [
    'success' => false,
    'message' => 'Invalid request'
];

try {
    $pdo = getDBConnection();
    
    // Get JSON or POST input
    $rawInput = file_get_contents('php://input');
    $data = [];
    if (!empty($rawInput)) {
        $json = json_decode($rawInput, true);
        if (is_array($json)) {
            $data = $json;
        }
    }
    if (empty($data)) {
        $data = $_POST;
    }

    $action = $_GET['action'] ?? $data['action'] ?? 'subscribe';

    // Get client IP
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    switch ($action) {
        case 'subscribe':
            $deviceId = trim($data['device_id'] ?? '');
            if (empty($deviceId)) {
                throw new Exception('Device ID is required');
            }

            $deviceType = trim($data['device_type'] ?? 'Desktop');
            $deviceName = trim($data['device_name'] ?? 'Unknown Device');
            $browser    = trim($data['browser'] ?? 'Unknown Browser');
            $os         = trim($data['os'] ?? 'Unknown OS');

            // Insert or update device subscription
            $stmt = $pdo->prepare("
                INSERT INTO `subscribers` (`device_id`, `device_type`, `device_name`, `browser`, `os`, `ip_address`, `user_agent`, `status`, `updated_at`)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW())
                ON DUPLICATE KEY UPDATE
                    `device_type` = VALUES(`device_type`),
                    `device_name` = VALUES(`device_name`),
                    `browser`     = VALUES(`browser`),
                    `os`          = VALUES(`os`),
                    `ip_address`  = VALUES(`ip_address`),
                    `user_agent`  = VALUES(`user_agent`),
                    `status`      = 'active',
                    `updated_at`  = NOW()
            ");
            $stmt->execute([$deviceId, $deviceType, $deviceName, $browser, $os, $ip, $userAgent]);

            // Count total active subscribers
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `subscribers` WHERE `status` = 'active'");
            $totalActive = (int)$countStmt->fetchColumn();

            $response = [
                'success' => true,
                'message' => 'डिवाइस सफलतापूर्वक सब्सक्राइब हो गया है!',
                'device_id' => $deviceId,
                'status' => 'active',
                'total_subscribers' => $totalActive
            ];
            break;

        case 'unsubscribe':
            $deviceId = trim($data['device_id'] ?? '');
            if (empty($deviceId)) {
                throw new Exception('Device ID is required');
            }

            $stmt = $pdo->prepare("UPDATE `subscribers` SET `status` = 'unsubscribed', `updated_at` = NOW() WHERE `device_id` = ?");
            $stmt->execute([$deviceId]);

            $response = [
                'success' => true,
                'message' => 'डिवाइस अनसब्सक्राइब हो गया है।',
                'device_id' => $deviceId,
                'status' => 'unsubscribed'
            ];
            break;

        case 'check':
            $deviceId = trim($_GET['device_id'] ?? $data['device_id'] ?? '');
            if (empty($deviceId)) {
                throw new Exception('Device ID is required');
            }

            $stmt = $pdo->prepare("SELECT `device_id`, `device_type`, `device_name`, `browser`, `os`, `status`, `created_at` FROM `subscribers` WHERE `device_id` = ?");
            $stmt->execute([$deviceId]);
            $subscriber = $stmt->fetch();

            if ($subscriber) {
                $response = [
                    'success' => true,
                    'is_subscribed' => ($subscriber['status'] === 'active'),
                    'subscriber' => $subscriber
                ];
            } else {
                $response = [
                    'success' => true,
                    'is_subscribed' => false,
                    'subscriber' => null
                ];
            }
            break;

        case 'stats':
            $stmt = $pdo->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN `status` = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN `device_type` = 'Mobile' AND `status` = 'active' THEN 1 ELSE 0 END) as mobile,
                    SUM(CASE WHEN `device_type` = 'Desktop' AND `status` = 'active' THEN 1 ELSE 0 END) as desktop
                FROM `subscribers`
            ");
            $stats = $stmt->fetch();
            $response = [
                'success' => true,
                'stats' => $stats
            ];
            break;

        default:
            throw new Exception('Invalid action specified');
    }

} catch (Throwable $e) {
    http_response_code(400);
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
