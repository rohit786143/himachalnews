<?php
/**
 * Disclaimer Page (disclaimer.php)
 * Himachal News Portal - Khabar 24
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$page = getPageBySlug($pdo, 'disclaimer');

$pageTitle = 'अस्वीकरण (Disclaimer) - Khabar 24 हिमाचल न्यूज़';
$pageDescription = 'Khabar 24 पोर्टल का कानूनी अस्वीकरण एवं संपादकीय नीति।';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        
        <div class="main-content-column">
            <div class="static-page-card">
                <h1 class="static-page-title">अस्वीकरण (Disclaimer)</h1>

                <?php if ($page && !empty($page['content'])): ?>
                    <div class="article-body-content">
                        <?= $page['content'] ?>
                    </div>
                <?php else: ?>
                    <div class="article-body-content">
                        <h2>कानूनी अस्वीकरण एवं उपयोग की शर्तें</h2>
                        <p>इस न्यूज़ पोर्टल (Khabar 24) पर प्रकाशित सभी समाचार, आलेख और विचार केवल सूचनात्मक उद्देश्य के लिए हैं। यद्यपि हम सटीकता सुनिश्चित करने का भरसक प्रयास करते हैं, फिर भी किसी अनजाने विसंगति के लिए पोर्टल उत्तरदायी नहीं होगा।</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-column">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
