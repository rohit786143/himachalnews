<?php
/**
 * Real-Time Bulletin Timeline Feed API
 * News 24 Himachal
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDBConnection();

$bulletin = getActiveLiveBulletin($pdo);

if (!$bulletin) {
    echo json_encode([
        'success' => false,
        'message' => 'कोई सक्रिय बुलेटिन नहीं मिला।'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$bulletinId = (int)$bulletin['id'];
$updates = getBulletinUpdates($pdo, $bulletinId, 60);

$badgeClassMap = [
    'बड़ी खबर' => 'badge-timeline-breaking',
    'ब्रेकिंग' => 'badge-timeline-breaking',
    'अपडेट' => 'badge-timeline-update',
    'मौसम' => 'badge-timeline-weather',
    'खेल' => 'badge-timeline-sports',
    'सियासत' => 'badge-timeline-politics',
    'राजनीति' => 'badge-timeline-politics'
];

$formattedUpdates = [];
foreach ($updates as $u) {
    $badgeType = $u['badge_type'] ?? 'अपडेट';
    $badgeClass = $badgeClassMap[$badgeType] ?? 'badge-timeline-update';

    $formattedUpdates[] = [
        'id' => (int)$u['id'],
        'timestamp_label' => sanitize($u['timestamp_label']),
        'headline' => sanitize($u['headline']),
        'badge_type' => sanitize($badgeType),
        'badge_class' => $badgeClass,
        'created_at' => $u['created_at']
    ];
}

echo json_encode([
    'success' => true,
    'bulletin' => [
        'id' => $bulletinId,
        'title' => sanitize($bulletin['title']),
        'is_live' => (bool)$bulletin['is_live'],
        'video_url' => $bulletin['video_url'],
        'embed_url' => normalizeVideoEmbedUrl($bulletin['video_url']),
        'description' => sanitize($bulletin['description'] ?? '')
    ],
    'updates' => $formattedUpdates,
    'total_updates' => count($formattedUpdates),
    'server_time' => date('h:i:s A')
], JSON_UNESCAPED_UNICODE);
