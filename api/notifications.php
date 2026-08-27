<?php
/**
 * Push Notifications Engine API Endpoint
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

    $action = $_GET['action'] ?? $data['action'] ?? 'check_new';

    switch ($action) {
        // 1. Client checks for any newly sent manual notifications
        case 'check_new':
            $deviceId = trim($_GET['device_id'] ?? $data['device_id'] ?? '');
            if (empty($deviceId)) {
                throw new Exception('Device ID is required');
            }

            // Verify subscriber status
            $subCheck = $pdo->prepare("SELECT `status` FROM `subscribers` WHERE `device_id` = ?");
            $subCheck->execute([$deviceId]);
            $subStatus = $subCheck->fetchColumn();

            if ($subStatus !== 'active') {
                $response = [
                    'success' => true,
                    'has_new' => false,
                    'notifications' => []
                ];
                break;
            }

            // Find notifications created in the last 48 hours that have NOT yet been delivered to this device
            $stmt = $pdo->prepare("
                SELECT n.`id`, n.`news_id`, n.`title`, n.`message`, n.`url`, n.`image_url`, n.`badge_text`, n.`created_at`
                FROM `manual_notifications` n
                LEFT JOIN `notification_deliveries` d 
                    ON n.`id` = d.`notification_id` AND d.`device_id` = ?
                WHERE d.`id` IS NULL 
                  AND n.`created_at` >= (NOW() - INTERVAL 48 HOUR)
                ORDER BY n.`created_at` ASC
                LIMIT 5
            ");
            $stmt->execute([$deviceId]);
            $pendingNotifs = $stmt->fetchAll();

            if (!empty($pendingNotifs)) {
                // Record delivery for these notifications
                $deliverStmt = $pdo->prepare("
                    INSERT INTO `notification_deliveries` (`notification_id`, `device_id`, `delivered_at`)
                    VALUES (?, ?, NOW())
                    ON DUPLICATE KEY UPDATE `delivered_at` = VALUES(`delivered_at`)
                ");
                foreach ($pendingNotifs as $notif) {
                    $deliverStmt->execute([$notif['id'], $deviceId]);
                }

                $response = [
                    'success' => true,
                    'has_new' => true,
                    'notifications' => $pendingNotifs
                ];
            } else {
                $response = [
                    'success' => true,
                    'has_new' => false,
                    'notifications' => []
                ];
            }
            break;

        // 2. Track Notification Click
        case 'track_click':
            $deviceId = trim($data['device_id'] ?? $_GET['device_id'] ?? '');
            $notifId = (int)($data['notification_id'] ?? $_GET['notification_id'] ?? 0);

            if ($deviceId && $notifId > 0) {
                $stmt = $pdo->prepare("
                    UPDATE `notification_deliveries` 
                    SET `clicked_at` = NOW() 
                    WHERE `notification_id` = ? AND `device_id` = ?
                ");
                $stmt->execute([$notifId, $deviceId]);
            }

            $response = ['success' => true];
            break;

        // 3. Admin: Send Manual Notification
        case 'send_manual':
            $title = trim($data['title'] ?? '');
            $message = trim($data['message'] ?? '');
            $url = trim($data['url'] ?? '');
            $imageUrl = trim($data['image_url'] ?? '');
            $badgeText = trim($data['badge_text'] ?? 'ताज़ा खबर');
            $newsId = !empty($data['news_id']) ? (int)$data['news_id'] : null;

            if (empty($title)) {
                throw new Exception('शीर्षक (Title) अनिवार्य है');
            }
            if (empty($message)) {
                throw new Exception('संदेश (Message) अनिवार्य है');
            }
            if (empty($url)) {
                $url = 'http://localhost:8000';
            }

            // Get total active recipient count
            $countStmt = $pdo->query("SELECT COUNT(*) FROM `subscribers` WHERE `status` = 'active'");
            $recipientCount = (int)$countStmt->fetchColumn();

            $insertStmt = $pdo->prepare("
                INSERT INTO `manual_notifications` (`news_id`, `title`, `message`, `url`, `image_url`, `badge_text`, `sent_by`, `recipient_count`, `created_at`)
                VALUES (?, ?, ?, ?, ?, ?, 'Admin', ?, NOW())
            ");
            $insertStmt->execute([$newsId, $title, $message, $url, $imageUrl, $badgeText, $recipientCount]);
            $insertedId = (int)$pdo->lastInsertId();

            $response = [
                'success' => true,
                'message' => "नोटिफिकेशन सफलतापूर्वक {$recipientCount} डिवाइस(ओं) के लिए शेड्यूल व सेंड कर दिया गया!",
                'notification_id' => $insertedId,
                'recipient_count' => $recipientCount
            ];
            break;

        // 4. Test Notification for a specific device
        case 'send_test':
            $deviceId = trim($data['device_id'] ?? $_GET['device_id'] ?? '');
            if (empty($deviceId)) {
                throw new Exception('Device ID is required');
            }

            $testTitle = '🔔 परीक्षण अलर्ट (Test Alert) - News 24 Himachal';
            $testMsg = 'यह एक परीक्षण सूचना है। आपके डिवाइस पर लाइव नोटिफिकेशन सफलतापूर्वक सक्रिय है।';
            $testUrl = 'http://localhost:8000';
            $testImg = 'assets/images/logo.png';

            $insertStmt = $pdo->prepare("
                INSERT INTO `manual_notifications` (`title`, `message`, `url`, `image_url`, `badge_text`, `sent_by`, `recipient_count`, `created_at`)
                VALUES (?, ?, ?, ?, 'टेस्ट अलर्ट', 'Admin (Test)', 1, NOW())
            ");
            $insertStmt->execute([$testTitle, $testMsg, $testUrl, $testImg]);
            $notifId = (int)$pdo->lastInsertId();

            // Deliver immediately
            $delivStmt = $pdo->prepare("
                INSERT INTO `notification_deliveries` (`notification_id`, `device_id`, `delivered_at`)
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE `delivered_at` = VALUES(`delivered_at`)
            ");
            $delivStmt->execute([$notifId, $deviceId]);

            $response = [
                'success' => true,
                'message' => 'टेस्ट नोटिफिकेशन भेजा गया!',
                'notification' => [
                    'id' => $notifId,
                    'title' => $testTitle,
                    'message' => $testMsg,
                    'url' => $testUrl,
                    'image_url' => $testImg,
                    'badge_text' => 'टेस्ट अलर्ट'
                ]
            ];
            break;

        default:
            throw new Exception('Invalid action');
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
