<?php
/**
 * Search Results Page (search.php)
 * Himachal News Portal - Khabar 24
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$query = isset($_GET['q']) ? trim(sanitize($_GET['q'])) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 8;
$offset = ($page - 1) * $perPage;

$results = [];
$totalResults = 0;

if (!empty($query)) {
    $results = searchNews($pdo, $query, $perPage, $offset);
    $totalResults = countSearchResults($pdo, $query);
}

$totalPages = ceil($totalResults / $perPage);
$pageTitle = 'खोज परिणाम: ' . sanitize($query) . ' - Khabar 24';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container content-grid">
        
        <!-- Search Results Column -->
        <div class="main-content-column">
            
            <div class="section-header">
                <h1 class="section-title">
                    <i class="fas fa-search"></i> "<?= sanitize($query) ?>" के लिए खोज परिणाम
                </h1>
                <span style="font-size: 0.9rem; color: var(--text-muted);">
                    कुल <?= number_format($totalResults) ?> परिणाम मिले
                </span>
            </div>

            <?php if (!empty($results)): ?>
                <div class="news-cards-grid">
                    <?php foreach ($results as $item): ?>
                        <article class="news-card">
                            <div class="news-card-img">
                                <img src="<?= sanitize($item['image_url']) ?>" alt="<?= sanitize($item['title']) ?>" loading="lazy">
                            </div>
                            <div class="news-card-body">
                                <span class="category-tag">
                                    <?= sanitize($item['subcategory_name'] ?? $item['category_name']) ?>
                                </span>
                                <h2 class="news-card-title">
                                    <a href="article.php?slug=<?= urlencode($item['slug']) ?>">
                                        <?= sanitize($item['title']) ?>
                                    </a>
                                </h2>
                                <p class="news-card-excerpt"><?= sanitize($item['excerpt']) ?></p>
                                <div class="news-card-footer">
                                    <span><i class="far fa-clock"></i> <?= timeAgoHindi($item['created_at']) ?></span>
                                    <span><i class="far fa-eye"></i> <?= number_format($item['views']) ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?q=<?= urlencode($query) ?>&page=<?= $page - 1 ?>" class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?q=<?= urlencode($query) ?>&page=<?= $i ?>" class="page-link <?= $page == $i ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?q=<?= urlencode($query) ?>&page=<?= $page + 1 ?>" class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div style="background: var(--white); padding: 40px; text-align: center; border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <i class="fas fa-search" style="font-size: 3rem; color: #CBD5E0; margin-bottom: 15px;"></i>
                    <h3>कोई समाचार नहीं मिला</h3>
                    <p style="color: var(--text-muted); margin-top: 8px;">कृपया कोई अन्य कीवर्ड जैसे "शिमला", "कांगड़ा", "मौसम" या "दशहरा" लिखकर खोजें।</p>
                    <a href="index.php" class="btn-primary" style="margin-top: 18px;">मुख्य पृष्ठ पर जाएं</a>
                </div>
            <?php endif; ?>

        </div>

        <!-- Sidebar -->
        <div class="sidebar-column">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
