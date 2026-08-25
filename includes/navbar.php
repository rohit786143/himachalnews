<?php
/**
 * Navigation Bar Component
 * Multi-Level Categories & Subcategories
 */
$navCategories = getNavigationCategories($pdo);
$currentSlug = $_GET['cat'] ?? '';
$currentSubSlug = $_GET['sub'] ?? '';
?>
<nav class="main-nav" id="main-nav">
    <div class="container nav-inner">
        <button class="mobile-nav-toggle" id="mobile-nav-toggle" aria-label="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item <?= empty($currentSlug) && basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                <a href="index.php" class="nav-link"><i class="fas fa-home"></i> होम</a>
            </li>
            <li class="nav-item">
                <a href="index.php#livetv-section" class="nav-link nav-live-link"><span class="live-dot" style="background:#fff; width:6px; height:6px; border-radius:50%; display:inline-block; margin-right:4px;"></span> लाइव टीवी</a>
            </li>

            <?php foreach ($navCategories as $cat): ?>
                <?php 
                    $hasChildren = !empty($cat['subcategories']); 
                    $isActive = ($currentSlug === $cat['slug']);
                ?>
                <li class="nav-item <?= $isActive ? 'active' : '' ?> <?= $hasChildren ? 'has-dropdown' : '' ?>">
                    <a href="category.php?cat=<?= urlencode($cat['slug']) ?>" class="nav-link">
                        <?= sanitize($cat['name']) ?>
                        <?php if ($hasChildren): ?>
                            <i class="fas fa-angle-down"></i>
                        <?php endif; ?>
                    </a>
                    
                    <?php if ($hasChildren): ?>
                        <ul class="dropdown-menu">
                            <?php foreach ($cat['subcategories'] as $sub): ?>
                                <li>
                                    <a href="category.php?cat=<?= urlencode($cat['slug']) ?>&sub=<?= urlencode($sub['slug']) ?>" class="dropdown-link">
                                        <?= sanitize($sub['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
