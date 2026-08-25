<?php
/**
 * Admin Static Page Content Editor (About Us, Disclaimer, Privacy, Terms)
 * Himachal News - Khabar 24
 */

$isEdit = false;
$pageData = null;
$pageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDBConnection();

if ($pageId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM `pages` WHERE `id` = ? LIMIT 1");
    $stmt->execute([$pageId]);
    $pageData = $stmt->fetch();
    if ($pageData) {
        $isEdit = true;
    }
}

$adminTitle = $isEdit ? 'पेज संपादित करें: ' . ($pageData['title'] ?? '') : 'नया पेज जोड़ें';
$adminHeading = $isEdit ? 'पेज सामग्री संपादन (Edit Page Content)' : 'नया CMS पेज बनाएं';

$error = null;

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');

    if (empty($title)) {
        $error = "कृपया पेज का शीर्षक (Title) दर्ज करें।";
    } elseif (empty($content)) {
        $error = "कृपया पेज की सामग्री (Content) दर्ज करें।";
    } else {
        if (empty($slug)) {
            $slug = slugify($title);
        }

        try {
            if ($isEdit) {
                $stmt = $pdo->prepare("
                    UPDATE `pages` 
                    SET `title` = ?, `slug` = ?, `content` = ?, `meta_description` = ?
                    WHERE `id` = ?
                ");
                $stmt->execute([$title, $slug, $content, $metaDescription, $pageId]);
                $_SESSION['flash_message'] = "पेज '{$title}' की सामग्री सफलतापूर्वक अपडेट कर दी गई!";
                $_SESSION['flash_type'] = "success";
                header("Location: /admin/pages.php");
                exit;
            } else {
                $check = $pdo->prepare("SELECT COUNT(*) FROM `pages` WHERE `slug` = ?");
                $check->execute([$slug]);
                if ($check->fetchColumn() > 0) {
                    $slug .= '-' . time();
                }

                $stmt = $pdo->prepare("
                    INSERT INTO `pages` (`title`, `slug`, `content`, `meta_description`)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $content, $metaDescription]);
                $_SESSION['flash_message'] = "नया पेज '{$title}' सफलतापूर्वक बना दिया गया!";
                $_SESSION['flash_type'] = "success";
                header("Location: /admin/pages.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "डेटाबेस त्रुटि: " . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <div><i class="fas fa-triangle-exclamation"></i> <?= sanitize($error) ?></div>
        <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="fas fa-times"></i></button>
    </div>
<?php endif; ?>

<form method="POST" action="" id="pageForm">
    <div style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 24px;">
        
        <!-- Left: Title and Rich Text Content -->
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-file-pen"></i> पेज विवरण एवं मुख्य सामग्री</h2>
                </div>
                <div class="panel-body">
                    
                    <!-- Page Title -->
                    <div class="form-group">
                        <label class="form-label" for="pageTitleInput">
                            पेज का शीर्षक (Page Title) <span class="required">*</span>
                        </label>
                        <input type="text" id="pageTitleInput" name="title" class="form-control" style="font-size: 1.05rem; font-weight: 600;" 
                               placeholder="उदा: हमारे बारे में (About Us)" 
                               value="<?= sanitize($pageData['title'] ?? ($_POST['title'] ?? '')) ?>" required>
                    </div>

                    <!-- Meta Description -->
                    <div class="form-group">
                        <label class="form-label" for="metaDescInput">
                            मेटा विवरण (Meta Description for SEO)
                        </label>
                        <textarea id="metaDescInput" name="meta_description" class="form-control" style="min-height: 60px;" 
                                  placeholder="सर्च इंजन और सोशल शेयरिंग के लिए संक्षिप्त विवरण..."><?= sanitize($pageData['meta_description'] ?? ($_POST['meta_description'] ?? '')) ?></textarea>
                    </div>

                    <!-- Rich Content with Quill Editor -->
                    <div class="form-group">
                        <label class="form-label">
                            पेज की पूरी सामग्री (Full Page Content / HTML) <span class="required">*</span>
                        </label>
                        <div class="quill-wrapper">
                            <div id="pageToolbar">
                                <span class="ql-formats">
                                    <select class="ql-header">
                                        <option value="1">Heading 1</option>
                                        <option value="2">Heading 2</option>
                                        <option value="3">Heading 3</option>
                                        <option selected>Normal Text</option>
                                    </select>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                    <button class="ql-blockquote"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                    <button class="ql-clean"></button>
                                </span>
                            </div>
                            <div id="pageQuill" style="min-height: 380px;">
                                <?= $pageData['content'] ?? ($_POST['content'] ?? '') ?>
                            </div>
                        </div>
                        <input type="hidden" name="content" id="pageHiddenContent">
                    </div>

                </div>
            </div>
        </div>

        <!-- Right: Slug & Save Button -->
        <div>
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-floppy-disk"></i> सुरक्षित करें (Save Page)</h2>
                </div>
                <div class="panel-body">
                    
                    <!-- Slug -->
                    <div class="form-group">
                        <label class="form-label" for="pageSlugInput">
                            URL स्लग (Slug) <span class="required">*</span>
                        </label>
                        <input type="text" id="pageSlugInput" name="slug" class="form-control" 
                               placeholder="about, privacy-policy" 
                               value="<?= sanitize($pageData['slug'] ?? ($_POST['slug'] ?? '')) ?>" required>
                        <span class="form-hint">वेबसाइट रूट पर URL बनेगा (उदा: about.php या page.php?slug=about)</span>
                    </div>

                    <button type="submit" class="topbar-btn" style="width: 100%; justify-content: center; padding: 12px; font-size: 1rem; margin-top: 10px;">
                        <i class="fas fa-check-circle"></i> <?= $isEdit ? 'पेज अपडेट करें (Update Page)' : 'पेज बनाएं (Create Page)' ?>
                    </button>

                    <div style="margin-top: 14px; text-align: center;">
                        <a href="/admin/pages.php" style="color: var(--text-dim); font-size: 0.85rem; text-decoration: none;">
                            <i class="fas fa-arrow-left"></i> वापस सभी पेज पर जाएं
                        </a>
                    </div>
                </div>
            </div>

            <!-- Page Quick Preview Links -->
            <?php if ($isEdit): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h2 class="panel-title"><i class="fas fa-eye"></i> लाइव लिंक</h2>
                    </div>
                    <div class="panel-body">
                        <?php 
                        $slugMap = [
                            'about' => '/about.php',
                            'disclaimer' => '/disclaimer.php',
                            'privacy-policy' => '/privacy-policy.php',
                            'terms' => '/terms.php'
                        ];
                        $targetUrl = $slugMap[$pageData['slug']] ?? ('/index.php');
                        ?>
                        <a href="<?= $targetUrl ?>" target="_blank" class="view-site-btn">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                            <span>लाइव पेज देखें (<?= sanitize($pageData['title']) ?>)</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
// Initialize Page Quill Editor
const pageQuill = new Quill('#pageQuill', {
    modules: {
        toolbar: '#pageToolbar'
    },
    theme: 'snow',
    placeholder: 'पेज की सामग्री यहाँ दर्ज करें...'
});

const pageForm = document.getElementById('pageForm');
pageForm.onsubmit = function() {
    document.getElementById('pageHiddenContent').value = pageQuill.root.innerHTML;
};
</script>
