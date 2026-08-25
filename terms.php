<?php
/**
 * Terms of Service Page (terms.php)
 * Himachal News Portal - Khabar 24
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$page = getPageBySlug($pdo, 'terms');

$pageTitle = 'नियम एवं शर्तें (Terms of Service) - Khabar 24';
$pageDescription = 'Khabar 24 वेबसाइट उपयोग की नियम व शर्तें।';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        <div class="main-content-column">
            <div class="static-page-card">
                <h1 class="static-page-title">नियम एवं शर्तें (Terms of Service)</h1>

                <?php if ($page && !empty($page['content'])): ?>
                    <div class="article-body-content">
                        <?= $page['content'] ?>
                    </div>
                <?php else: ?>
                    <div class="article-body-content">
                        <h2>वेबसाइट उपयोग की सामान्य शर्तें</h2>
                        <p>Khabar 24 पोर्टल का उपयोग करने वाले सभी उपयोगकर्ताओं से अपेक्षा की जाती है कि वे डिजिटल नियमों और कॉपीराइट नीतियों का सम्मान करें।</p>
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
