<?php
/**
 * About Us Page (about.php)
 * Himachal News Portal - Khabar 24
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$page = getPageBySlug($pdo, 'about');

$pageTitle = 'हमारे बारे में (About Us) - Khabar 24 हिमाचल न्यूज़';
$pageDescription = 'Khabar 24 देवभूमि हिमाचल प्रदेश का प्रमुख डिजिटल न्यूज़ पोर्टल है। जानें हमारी संपादकीय टीम और मूल्यों के बारे में।';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        
        <div class="main-content-column">
            <div class="static-page-card">
                <h1 class="static-page-title">हमारे बारे में (About Us)</h1>

                <!-- CMS Content -->
                <?php if ($page && !empty($page['content'])): ?>
                    <div class="article-body-content">
                        <?= $page['content'] ?>
                    </div>
                <?php else: ?>
                    <div class="article-body-content">
                        <h2>सत्य, निष्पक्षता और देवभूमि के सरोकार</h2>
                        <p><strong>Khabar 24</strong> हिमाचल प्रदेश का अग्रणी हिंदी समाचार नेटवर्क है। हमारा लक्ष्य प्रदेश के 12 जिलों से लेकर राजधानी शिमला और देश-विदेश की हर बड़ी खबर को निष्पक्ष रूप से आप तक पहुंचाना है।</p>
                    </div>
                <?php endif; ?>

                <!-- Stats Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin: 35px 0; text-align: center;">
                    <div style="background: var(--light-bg); padding: 20px 10px; border-radius: var(--radius); border-top: 3px solid var(--primary-red);">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--primary-red); font-family: 'Poppins', sans-serif;">12</div>
                        <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-primary);">सभी जिले कवर</div>
                    </div>
                    <div style="background: var(--light-bg); padding: 20px 10px; border-radius: var(--radius); border-top: 3px solid var(--primary-red);">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--primary-red); font-family: 'Poppins', sans-serif;">24x7</div>
                        <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-primary);">लाइव ब्रेकिंग न्यूज़</div>
                    </div>
                    <div style="background: var(--light-bg); padding: 20px 10px; border-radius: var(--radius); border-top: 3px solid var(--primary-red);">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--primary-red); font-family: 'Poppins', sans-serif;">500K+</div>
                        <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-primary);">मासिक पाठक</div>
                    </div>
                    <div style="background: var(--light-bg); padding: 20px 10px; border-radius: var(--radius); border-top: 3px solid var(--primary-red);">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--primary-red); font-family: 'Poppins', sans-serif;">100%</div>
                        <div style="font-size: 0.88rem; font-weight: 600; color: var(--text-primary);">सत्यापित पत्रकारिता</div>
                    </div>
                </div>

                <!-- Editorial Leadership Team -->
                <h3 style="font-size: 1.4rem; font-weight: 700; margin: 30px 0 18px; border-bottom: 2px solid var(--border-color); padding-bottom: 8px;">
                    हमारी संपादकीय टीम (Editorial Team)
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                    <div style="background: var(--light-bg); padding: 20px; border-radius: var(--radius); text-align: center;">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--dark-charcoal); color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.6rem;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 4px;">रोहित वर्मा</h4>
                        <div style="color: var(--primary-red); font-size: 0.85rem; font-weight: 600;">प्रधान संपादक (Editor-in-Chief)</div>
                    </div>
                    <div style="background: var(--light-bg); padding: 20px; border-radius: var(--radius); text-align: center;">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--dark-charcoal); color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.6rem;">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 4px;">अनिल शर्मा</h4>
                        <div style="color: var(--primary-red); font-size: 0.85rem; font-weight: 600;">ब्यूरो प्रमुख (कांगड़ा संभाग)</div>
                    </div>
                    <div style="background: var(--light-bg); padding: 20px; border-radius: var(--radius); text-align: center;">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--dark-charcoal); color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.6rem;">
                            <i class="fas fa-feather-alt"></i>
                        </div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 4px;">प्रिया ठाकुर</h4>
                        <div style="color: var(--primary-red); font-size: 0.85rem; font-weight: 600;">संस्कृति एवं पर्यटन डेस्क</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-column">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
