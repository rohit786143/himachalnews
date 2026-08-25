<?php
/**
 * Article Details Page (article.php)
 * Himachal News Portal - Khabar 24
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

$article = getArticleBySlug($pdo, $slug);

// Handle 404 if article not found
if (!$article) {
    header("HTTP/1.0 404 Not Found");
    $pageTitle = 'समाचार नहीं मिला - Khabar 24';
    require_once __DIR__ . '/includes/header.php';
    echo '<div class="container" style="padding: 80px 20px; text-align: center;">
            <i class="fas fa-exclamation-triangle" style="font-size: 3.5rem; color: #E50914; margin-bottom: 20px;"></i>
            <h1 style="font-size: 2rem; margin-bottom: 12px;">क्षमा करें, यह समाचार उपलब्ध नहीं है।</h1>
            <p style="color: #666; margin-bottom: 25px;">हो सकता है कि यह लिंक टूट गया हो या खबर हटा दी गई हो।</p>
            <a href="index.php" class="btn-primary">होम पेज पर लौटें &rarr;</a>
          </div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Increment View Count
incrementArticleViews($pdo, $article['id']);

// Fetch Related Articles
$relatedArticles = getRelatedNews($pdo, $article['category_id'], $article['id'], 3);

$pageTitle = sanitize($article['title']) . ' - Khabar 24';
$pageDescription = sanitize($article['excerpt'] ?? mb_substr(strip_tags($article['content']), 0, 150) . '...');
$readingTime = estimateReadingTime($article['content']);

$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$encodedUrl = urlencode($currentUrl);
$encodedTitle = urlencode($article['title']);

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        
        <!-- Main Article Column -->
        <article class="main-content-column">
            
            <header class="article-header">
                <!-- Breadcrumbs -->
                <nav class="breadcrumbs" aria-label="Breadcrumb">
                    <a href="index.php">होम</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <a href="category.php?cat=<?= urlencode($article['category_slug']) ?>">
                        <?= sanitize($article['category_name']) ?>
                    </a>
                    <?php if (!empty($article['subcategory_name'])): ?>
                        <span class="separator"><i class="fas fa-chevron-right"></i></span>
                        <a href="category.php?cat=<?= urlencode($article['category_slug']) ?>&sub=<?= urlencode($article['subcategory_slug']) ?>">
                            <?= sanitize($article['subcategory_name']) ?>
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Category Tag -->
                <span class="category-tag">
                    <?= sanitize($article['subcategory_name'] ?? $article['category_name']) ?>
                </span>

                <!-- Article Headline -->
                <h1 class="article-main-title"><?= sanitize($article['title']) ?></h1>

                <!-- Meta Information Bar -->
                <?php
                $authorId = (int)($article['author_id'] ?? ($article['editor_id'] ?? 1));
                $authorAvatar = !empty($article['editor_avatar']) ? $article['editor_avatar'] : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80';
                $authorName = !empty($article['editor_name']) ? $article['editor_name'] : ($article['author'] ?? 'केलांग संवाददाता');
                $authorDesignation = !empty($article['editor_designation']) ? $article['editor_designation'] : 'संपादकीय डेस्क • Khabar 24';
                ?>
                <div class="article-meta-bar">
                    <a href="author.php?id=<?= $authorId ?>" class="meta-author-link" style="text-decoration: none; color: inherit; display: inline-flex;" title="<?= sanitize($authorName) ?> की प्रोफाइल एवं खबरें देखें">
                        <div class="meta-author-box" style="cursor: pointer; transition: transform 0.2s ease;">
                            <div class="author-avatar" style="overflow: hidden; padding: 0; background: #E2E8F0; border: 2px solid var(--primary-red); width: 44px; height: 44px; border-radius: 50%; box-shadow: 0 2px 6px rgba(229,9,20,0.25);">
                                <img src="<?= sanitize($authorAvatar) ?>" alt="<?= sanitize($authorName) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 5px;">
                                    <span><?= sanitize($authorName) ?></span>
                                    <i class="fas fa-arrow-up-right-from-square" style="font-size: 0.65rem; color: var(--primary-red);"></i>
                                </div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><?= sanitize($authorDesignation) ?></div>
                            </div>
                        </div>
                    </a>

                    <div class="meta-info-list">
                        <span><i class="far fa-calendar-alt"></i> <?= formatHindiDate($article['created_at']) ?></span>
                        <span><i class="far fa-clock"></i> <?= $readingTime ?> मिनट का समय</span>
                        <span><i class="far fa-eye"></i> <?= number_format($article['views'] + 1) ?> व्यूज</span>
                    </div>
                </div>
            </header>

            <!-- Social Share Bar (Top) -->
            <div class="social-share-box">
                <div class="share-label">
                    <i class="fas fa-share-alt"></i> इस खबर को शेयर करें:
                </div>
                <div class="share-buttons">
                    <a href="https://api.whatsapp.com/send?text=<?= $encodedTitle ?>%20-%20<?= $encodedUrl ?>" target="_blank" rel="noopener" class="share-btn whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank" rel="noopener" class="share-btn facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= $encodedTitle ?>&url=<?= $encodedUrl ?>" target="_blank" rel="noopener" class="share-btn twitter">
                        <i class="fab fa-x-twitter"></i> X / Twitter
                    </a>
                    <a href="https://t.me/share/url?url=<?= $encodedUrl ?>&text=<?= $encodedTitle ?>" target="_blank" rel="noopener" class="share-btn telegram">
                        <i class="fab fa-telegram-plane"></i> Telegram
                    </a>
                    <button class="share-btn copy copy-share-btn">
                        <i class="fas fa-link"></i> लिंक कॉपी करें
                    </button>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="article-featured-img">
                <img src="<?= sanitize($article['image_url']) ?>" alt="<?= sanitize($article['title']) ?>">
            </div>

            <!-- Article Body Content -->
            <div class="article-body-content">
                <?php if (!empty($article['excerpt'])): ?>
                    <p style="font-size: 1.25rem; font-weight: 500; color: #1F2937; border-left: 4px solid var(--primary-red); padding-left: 16px; margin-bottom: 24px; line-height: 1.7;">
                        <?= sanitize($article['excerpt']) ?>
                    </p>
                <?php endif; ?>

                <?= $article['content'] ?>
            </div>

            <!-- Author Bio Card Box -->
            <div style="background: #F8FAFC; border: 1.5px solid var(--border-color); border-radius: 12px; padding: 18px 20px; margin: 30px 0 20px; display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                <a href="author.php?id=<?= $authorId ?>">
                    <img src="<?= sanitize($authorAvatar) ?>" alt="<?= sanitize($authorName) ?>" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-red); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                </a>
                <div style="flex-grow: 1; min-width: 220px;">
                    <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: var(--primary-red); letter-spacing: 0.5px;">लेखक परिचय (About the Reporter)</div>
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-primary); margin: 2px 0 4px;">
                        <a href="author.php?id=<?= $authorId ?>" style="color: inherit; text-decoration: none;">
                            <?= sanitize($authorName) ?>
                        </a>
                    </h3>
                    <p style="font-size: 0.86rem; color: var(--text-muted); line-height: 1.45; margin-bottom: 8px;">
                        <?= sanitize($article['editor_bio'] ?: 'हिमाचल प्रदेश के प्रमुख मामलों और स्थानीय विकास पर निरंतर ग्राउंड रिपोर्टिंग।') ?>
                    </p>
                    <a href="author.php?id=<?= $authorId ?>" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 700; color: var(--primary-red); text-decoration: none;">
                        <span>लेखक की सभी खबरें पढ़ें</span> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Article Footer Tags & Bottom Share -->
            <div style="padding: 20px 0; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="font-weight: 700; font-size: 0.9rem;"><i class="fas fa-tags"></i> टैग्स:</span>
                    <a href="category.php?cat=<?= urlencode($article['category_slug']) ?>" class="district-tab">
                        #<?= sanitize($article['category_name']) ?>
                    </a>
                    <?php if (!empty($article['subcategory_name'])): ?>
                        <a href="category.php?cat=<?= urlencode($article['category_slug']) ?>&sub=<?= urlencode($article['subcategory_slug']) ?>" class="district-tab">
                            #<?= sanitize($article['subcategory_name']) ?>
                        </a>
                    <?php endif; ?>
                    <span class="district-tab">#हिमाचल_अपडेट</span>
                </div>
            </div>

            <!-- Related News Section -->
            <?php if (!empty($relatedArticles)): ?>
                <section class="related-news-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-layer-group"></i> संबंधित समाचार (Related News)
                        </h2>
                    </div>
                    <div class="news-cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                        <?php foreach ($relatedArticles as $rel): ?>
                            <div class="news-card">
                                <div class="news-card-img" style="height: 150px;">
                                    <img src="<?= sanitize($rel['image_url']) ?>" alt="<?= sanitize($rel['title']) ?>" loading="lazy">
                                </div>
                                <div class="news-card-body">
                                    <h3 class="news-card-title" style="font-size: 0.92rem;">
                                        <a href="article.php?slug=<?= urlencode($rel['slug']) ?>">
                                            <?= sanitize($rel['title']) ?>
                                        </a>
                                    </h3>
                                    <div class="news-card-footer">
                                        <span><i class="far fa-clock"></i> <?= timeAgoHindi($rel['created_at']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

        </article>

        <!-- Sidebar Column -->
        <div class="sidebar-column">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
