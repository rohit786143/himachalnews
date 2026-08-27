<?php
/**
 * Privacy Policy Page (privacy-policy.php)
 * News 24 Himachal
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$page = getPageBySlug($pdo, 'privacy-policy');

$pageTitle = 'गोपनीयता नीति (Privacy Policy) - News 24 Himachal';
$pageDescription = 'News 24 Himachal की गोपनीयता नीति और पाठकों के डेटा संरक्षण के नियम।';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        <div class="main-content-column">
            <div class="static-page-card">
                <h1 class="static-page-title">गोपनीयता नीति (Privacy Policy)</h1>

                <?php if ($page && !empty($page['content'])): ?>
                    <div class="article-body-content">
                        <?= $page['content'] ?>
                    </div>
                <?php else: ?>
                    <div class="article-body-content">
                        <h2>डेटा संरक्षण एवं पाठक निजता</h2>
                        <p>News 24 Himachal पर हम अपने पाठकों की गोपनीयता का सम्मान करते हैं। हम आपकी व्यक्तिगत जानकारी को किसी तीसरे पक्ष के साथ साझा नहीं करते हैं।</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="sidebar-column">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
