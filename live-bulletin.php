<?php
/**
 * Live Breaking Bulletin & Real-Time Timeline Page
 * News 24 Himachal
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();

// Fetch active bulletin
$bulletin = getActiveLiveBulletin($pdo);

if (!$bulletin) {
    $bulletin = [
        'id' => 1,
        'title' => 'हिमाचल लाइव ब्रेकिंग बुलेटिन: शिमला, मनाली, धर्मशाला से आज की सभी बड़ी खबरें',
        'is_live' => 1,
        'description' => 'देवभूमि हिमाचल प्रदेश का सबसे तेज़ व सटीक लाइव बुलेटिन। मौसम अपडेट, कैबिनेट के फैसले, यातायात एडवाइजरी और खेल जगत की हर ब्रेकिंग खबर।',
        'created_at' => date('Y-m-d H:i:s')
    ];
}

$bulletinId = (int)$bulletin['id'];
$isLive = (bool)$bulletin['is_live'];
$updates = getBulletinUpdates($pdo, $bulletinId, 60);

$pageTitle = '🔴 LIVE | ' . sanitize($bulletin['title']) . ' - News 24 Himachal लाइव बुलेटिन';
$pageDescription = sanitize($bulletin['description'] ?? 'हिमाचल प्रदेश की हर बड़ी खबर, लाइव बुलेटिन और मिनट-दर-मिनट ब्रेकिंग अपडेट्स।');

require_once __DIR__ . '/includes/header.php';
?>

<main class="site-main py-4">
    <div class="main-layout">
        <div class="container content-grid">
            
            <!-- Left Main Column: Live Bulletin Timeline Feed -->
            <div class="main-content-column">
                
                <!-- Live Bulletin Header Banner -->
                <div class="live-bulletin-header-box">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 12px;">
                        <div>
                            <span class="live-badge-glow">
                                <span class="live-pulse-dot" style="margin-right: 2px;"></span> LIVE BULLETIN
                            </span>
                        </div>
                        <div style="font-size: 0.82rem; color: #94A3B8; display: flex; align-items: center; gap: 6px;">
                            <i class="far fa-calendar-alt"></i> <?= formatHindiDate(date('Y-m-d')) ?>
                        </div>
                    </div>

                    <h1 style="font-size: 1.45rem; font-weight: 800; line-height: 1.4; margin-bottom: 8px; font-family: var(--font-heading);">
                        <?= sanitize($bulletin['title']) ?>
                    </h1>

                    <?php if (!empty($bulletin['description'])): ?>
                        <p style="font-size: 0.92rem; color: #CBD5E1; line-height: 1.6; margin-bottom: 0;">
                            <?= nl2br(sanitize($bulletin['description'])) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Real-Time Timeline Feed (मिनट-दर-मिनट लाइव अपडेट्स) -->
                <div class="live-timeline-wrapper" id="liveTimelineContainer">
                    <div class="timeline-header-bar">
                        <div>
                            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-bolt" style="color: var(--primary-red);"></i> मिनट-दर-मिनट लाइव अपडेट्स (Live Feed)
                            </h2>
                            <span style="font-size: 0.76rem; color: var(--text-muted);">ऑटो-रिफ्रेश सक्रिय • हर 15 सेकंड में ताज़ा अपडेट्स</span>
                        </div>
                        <div>
                            <span id="timelineUpdateCounter" class="badge" style="background: #F1F5F9; color: var(--text-primary); font-size: 0.76rem; padding: 4px 10px; border: 1px solid #CBD5E1;">
                                कुल: <strong id="totalUpdatesCount"><?= count($updates) ?></strong> अपडेट्स
                            </span>
                        </div>
                    </div>

                    <div class="timeline-feed-list" id="timelineFeedList">
                        <?php if (empty($updates)): ?>
                            <div class="timeline-empty" id="timelineEmptyNotice" style="padding: 24px; text-align: center; color: var(--text-muted);">
                                <i class="fas fa-clock" style="font-size: 2rem; color: #CBD5E1; margin-bottom: 8px;"></i>
                                <p>इस बुलेटिन के लिए अभी लाइव अपडेट्स आने शुरू हो रहे हैं...</p>
                            </div>
                        <?php else: ?>
                            <?php 
                            $badgeClasses = [
                                'बड़ी खबर' => 'badge-timeline-breaking',
                                'ब्रेकिंग' => 'badge-timeline-breaking',
                                'अपडेट' => 'badge-timeline-update',
                                'मौसम' => 'badge-timeline-weather',
                                'खेल' => 'badge-timeline-sports',
                                'सियासत' => 'badge-timeline-politics',
                                'राजनीति' => 'badge-timeline-politics'
                            ];
                            ?>
                            <?php foreach ($updates as $u): ?>
                                <?php $bClass = $badgeClasses[$u['badge_type']] ?? 'badge-timeline-update'; ?>
                                <div class="timeline-item" data-id="<?= $u['id'] ?>">
                                    <div class="timeline-node"></div>
                                    <div class="timeline-item-meta">
                                        <span class="timeline-time">
                                            <i class="far fa-clock"></i> <?= sanitize($u['timestamp_label']) ?>
                                        </span>
                                        <span class="badge-timeline <?= $bClass ?>">
                                            <?= sanitize($u['badge_type']) ?>
                                        </span>
                                    </div>
                                    <div class="timeline-headline-text">
                                        <?= sanitize($u['headline']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Column: All Widgets -->
            <aside class="sidebar-column">
                <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
            </aside>

        </div>
    </div>
</main>

<!-- Live Timeline Real-Time Auto-Polling Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentBulletinId = <?= $bulletinId ?>;
    const feedList = document.getElementById('timelineFeedList');
    const counterElem = document.getElementById('totalUpdatesCount');
    let knownUpdateIds = new Set();

    // Populate initial IDs
    document.querySelectorAll('.timeline-item').forEach(item => {
        const id = parseInt(item.getAttribute('data-id'));
        if (id) knownUpdateIds.add(id);
    });

    function fetchLiveTimelineFeed() {
        fetch('/api/bulletin-feed.php?t=' + Date.now())
            .then(res => res.json())
            .then(data => {
                if (!data || !data.success || !data.updates) return;

                if (data.bulletin && data.bulletin.id === currentBulletinId) {
                    const newUpdates = data.updates.filter(u => !knownUpdateIds.has(u.id));
                    
                    if (newUpdates.length > 0) {
                        const emptyNotice = document.getElementById('timelineEmptyNotice');
                        if (emptyNotice) emptyNotice.remove();

                        // Reverse to insert newest on top
                        newUpdates.reverse().forEach(u => {
                            knownUpdateIds.add(u.id);

                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'timeline-item';
                            itemDiv.setAttribute('data-id', u.id);
                            itemDiv.innerHTML = `
                                <div class="timeline-node"></div>
                                <div class="timeline-item-meta">
                                    <span class="timeline-time">
                                        <i class="far fa-clock"></i> ${u.timestamp_label}
                                    </span>
                                    <span class="badge-timeline ${u.badge_class}">
                                        ${u.badge_type}
                                    </span>
                                </div>
                                <div class="timeline-headline-text">
                                    ${u.headline}
                                </div>
                            `;

                            // Prepend to feed with animation
                            feedList.insertBefore(itemDiv, feedList.firstChild);
                        });

                        if (counterElem) {
                            counterElem.textContent = knownUpdateIds.size;
                        }
                    }
                }
            })
            .catch(err => console.log('Timeline poll error:', err));
    }

    // Poll every 15 seconds
    setInterval(fetchLiveTimelineFeed, 15000);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
