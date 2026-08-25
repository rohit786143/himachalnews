<?php
/**
 * Breaking News Ticker Component
 */
$breakingItems = getBreakingNews($pdo, 8);
?>
<?php if (!empty($breakingItems)): ?>
<div class="breaking-ticker">
    <div class="container">
        <div class="ticker-inner">
            <div class="ticker-label">
                <i class="fas fa-bolt"></i> ब्रेकिंग न्यूज़
            </div>
            <div class="ticker-content">
                <div class="ticker-track">
                    <?php foreach ($breakingItems as $item): ?>
                        <a href="article.php?slug=<?= urlencode($item['slug']) ?>" class="ticker-item">
                            <span class="ticker-bullet"><i class="fas fa-circle"></i></span>
                            <span class="ticker-title"><?= sanitize($item['title']) ?></span>
                        </a>
                    <?php endforeach; ?>
                    <!-- Duplicate items for seamless continuous ticker loop -->
                    <?php foreach ($breakingItems as $item): ?>
                        <a href="article.php?slug=<?= urlencode($item['slug']) ?>" class="ticker-item">
                            <span class="ticker-bullet"><i class="fas fa-circle"></i></span>
                            <span class="ticker-title"><?= sanitize($item['title']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
