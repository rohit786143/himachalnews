<?php
/**
 * Admin Site Settings & Contact Details Editor
 * Himachal News - Khabar 24
 */

$adminTitle = 'साइट सेटिंग्स (Settings)';
$adminHeading = 'वेबसाइट सेटिंग्स एवं संपर्क विवरण (Site Configuration)';

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDBConnection();
$error = null;

// Handle Settings Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settingsToSave = [
        'site_name' => trim($_POST['site_name'] ?? 'News 24 Himachal'),
        'site_tagline' => trim($_POST['site_tagline'] ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'contact_address' => trim($_POST['contact_address'] ?? ''),
        'social_facebook' => trim($_POST['social_facebook'] ?? ''),
        'social_twitter' => trim($_POST['social_twitter'] ?? ''),
        'social_youtube' => trim($_POST['social_youtube'] ?? ''),
        'social_instagram' => trim($_POST['social_instagram'] ?? ''),
        'social_telegram' => trim($_POST['social_telegram'] ?? ''),
        'livetv_url' => trim($_POST['livetv_url'] ?? ''),
        'poll_question' => trim($_POST['poll_question'] ?? ''),
        'poll_opt1' => trim($_POST['poll_opt1'] ?? ''),
        'poll_opt1_val' => trim($_POST['poll_opt1_val'] ?? '0'),
        'poll_opt2' => trim($_POST['poll_opt2'] ?? ''),
        'poll_opt2_val' => trim($_POST['poll_opt2_val'] ?? '0'),
        'poll_opt3' => trim($_POST['poll_opt3'] ?? ''),
        'poll_opt3_val' => trim($_POST['poll_opt3_val'] ?? '0'),
        'poll_total_votes' => trim($_POST['poll_total_votes'] ?? '0')
    ];

    try {
        foreach ($settingsToSave as $key => $val) {
            setSetting($pdo, $key, $val);
        }
        $_SESSION['flash_message'] = "साइट सेटिंग्स एवं संपर्क विवरण सफलतापूर्वक अपडेट कर दिए गए!";
        $_SESSION['flash_type'] = "success";
        header("Location: /admin/settings.php");
        exit;
    } catch (PDOException $e) {
        $error = "त्रुटि: सेटिंग्स सेव नहीं हो सकीं। " . $e->getMessage();
    }
}

// Fetch current values
$siteName = getSetting($pdo, 'site_name', 'News 24 Himachal');
$siteTagline = getSetting($pdo, 'site_tagline', 'हिमाचल प्रदेश का नंबर 1 हिंदी न्यूज़ पोर्टल');
$contactEmail = getSetting($pdo, 'contact_email', 'editor@news24himachal.com');
$contactPhone = getSetting($pdo, 'contact_phone', '+91 177 265 8900');
$contactAddress = getSetting($pdo, 'contact_address', 'प्रेस एवेन्यू, माल रोड, शिमला, हिमाचल प्रदेश - 171001');
$socialFacebook = getSetting($pdo, 'social_facebook', 'https://www.facebook.com');
$socialTwitter = getSetting($pdo, 'social_twitter', 'https://twitter.com');
$socialYoutube = getSetting($pdo, 'social_youtube', 'https://youtube.com');
$socialInstagram = getSetting($pdo, 'social_instagram', 'https://instagram.com');
$socialTelegram = getSetting($pdo, 'social_telegram', 'https://telegram.org');
$liveTvUrl = getSetting($pdo, 'livetv_url', 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fshare%2Fv%2F1MJyM4wWgR%2F&show_text=false&autoplay=true&mute=1&loop=true&width=500');
$pollQuestion = getSetting($pdo, 'poll_question', 'क्या हिमाचल में विंटर टूरिज्म व स्नो-स्पोर्ट्स के लिए नई नीतियां बननी चाहिए?');
$pollOpt1 = getSetting($pdo, 'poll_opt1', 'हाँ (Yes)');
$pollOpt1Val = getSetting($pdo, 'poll_opt1_val', '74');
$pollOpt2 = getSetting($pdo, 'poll_opt2', 'नहीं (No)');
$pollOpt2Val = getSetting($pdo, 'poll_opt2_val', '18');
$pollOpt3 = getSetting($pdo, 'poll_opt3', 'कह नहीं सकते');
$pollOpt3Val = getSetting($pdo, 'poll_opt3_val', '8');
$pollTotalVotes = getSetting($pdo, 'poll_total_votes', '2840');

require_once __DIR__ . '/includes/header.php';
?>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <div><i class="fas fa-triangle-exclamation"></i> <?= sanitize($error) ?></div>
        <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;cursor:pointer;"><i class="fas fa-times"></i></button>
    </div>
<?php endif; ?>

<form method="POST" action="settings.php">
    <input type="hidden" name="save_settings" value="1">

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        
        <!-- Column 1: Site Info & Contact Us Details -->
        <div>
            
            <!-- General Info -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-globe"></i> सामान्य पोर्टल जानकारी (General Info)</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label" for="siteNameInput">पोर्टल का नाम (Site Name)</label>
                        <input type="text" id="siteNameInput" name="site_name" class="form-control" value="<?= sanitize($siteName) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="siteTaglineInput">टैगलाइन / स्लोगन (Tagline)</label>
                        <input type="text" id="siteTaglineInput" name="site_tagline" class="form-control" value="<?= sanitize($siteTagline) ?>">
                    </div>
                </div>
            </div>

            <!-- Contact Us Information -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-address-book"></i> संपर्क विवरण (Contact Us Details)</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label" for="contactEmailInput">
                            <i class="fas fa-envelope" style="color: var(--accent-blue);"></i> आधिकारिक ईमेल (Email)
                        </label>
                        <input type="email" id="contactEmailInput" name="contact_email" class="form-control" value="<?= sanitize($contactEmail) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contactPhoneInput">
                            <i class="fas fa-phone" style="color: var(--accent-green);"></i> हेल्पलाइन / फोन नंबर (Phone)
                        </label>
                        <input type="text" id="contactPhoneInput" name="contact_phone" class="form-control" value="<?= sanitize($contactPhone) ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contactAddressInput">
                            <i class="fas fa-location-dot" style="color: var(--primary);"></i> प्रेस / कार्यालय का पता (Physical Address)
                        </label>
                        <textarea id="contactAddressInput" name="contact_address" class="form-control" style="min-height: 80px;"><?= sanitize($contactAddress) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media Profiles -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-share-nodes"></i> सोशल मीडिया लिंक्स (Social Handles)</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-facebook-f" style="color: #1877F2;"></i> Facebook URL</label>
                        <input type="url" name="social_facebook" class="form-control" value="<?= sanitize($socialFacebook) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-x-twitter" style="color: #fff;"></i> Twitter / X URL</label>
                        <input type="url" name="social_twitter" class="form-control" value="<?= sanitize($socialTwitter) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-youtube" style="color: #FF0000;"></i> YouTube Channel URL</label>
                        <input type="url" name="social_youtube" class="form-control" value="<?= sanitize($socialYoutube) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-instagram" style="color: #E4405F;"></i> Instagram Profile URL</label>
                        <input type="url" name="social_instagram" class="form-control" value="<?= sanitize($socialInstagram) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fab fa-telegram" style="color: #24A1DE;"></i> Telegram Channel URL</label>
                        <input type="url" name="social_telegram" class="form-control" value="<?= sanitize($socialTelegram) ?>">
                    </div>
                </div>
            </div>

        </div>

        <!-- Column 2: Live TV, Opinion Poll & Save Actions -->
        <div>
            
            <!-- Save Button Card -->
            <div class="panel" style="position: sticky; top: 85px; z-index: 800; border-color: rgba(229,9,20,0.35); background: linear-gradient(180deg, var(--bg-card) 0%, rgba(229,9,20,0.06) 100%);">
                <div class="panel-body">
                    <h3 style="color: #fff; font-size: 1.05rem; margin-bottom: 8px; font-weight: 700;">
                        <i class="fas fa-floppy-disk" style="color: var(--primary);"></i> सेटिंग्स सुरक्षित करें
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 16px;">
                        यहाँ किए गए बदलाव तुरंत वेबसाइट के हेडर, फुटर, संपर्क पेज और लाइव फीड पर लागू होंगे।
                    </p>
                    <button type="submit" class="topbar-btn" style="width: 100%; justify-content: center; padding: 14px; font-size: 1rem;">
                        <i class="fas fa-check-circle"></i> सभी सेटिंग्स सुरक्षित करें (Save All)
                    </button>
                </div>
            </div>

            <!-- Live TV Broadcast Config -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-tv"></i> लाइव टीवी प्रसारण (Live TV Stream)</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label" for="liveTvInput">Live Video Embed URL</label>
                        <input type="text" id="liveTvInput" name="livetv_url" class="form-control" value="<?= sanitize($liveTvUrl) ?>">
                        <span class="form-hint">Facebook Live, YouTube Live Embed या HLS स्ट्रीम का लिंक।</span>
                    </div>
                </div>
            </div>

            <!-- Opinion Poll Config -->
            <div class="panel">
                <div class="panel-header">
                    <h2 class="panel-title"><i class="fas fa-poll-h"></i> जनमत पोल प्रश्न एवं विकल्प (Opinion Poll)</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label">पोल का मुख्य सवाल (Question)</label>
                        <textarea name="poll_question" class="form-control" style="min-height: 70px;"><?= sanitize($pollQuestion) ?></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label class="form-label">विकल्प 1 (Option 1)</label>
                            <input type="text" name="poll_opt1" class="form-control" value="<?= sanitize($pollOpt1) ?>">
                        </div>
                        <div>
                            <label class="form-label">% वोट</label>
                            <input type="number" name="poll_opt1_val" class="form-control" value="<?= sanitize($pollOpt1Val) ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label class="form-label">विकल्प 2 (Option 2)</label>
                            <input type="text" name="poll_opt2" class="form-control" value="<?= sanitize($pollOpt2) ?>">
                        </div>
                        <div>
                            <label class="form-label">% वोट</label>
                            <input type="number" name="poll_opt2_val" class="form-control" value="<?= sanitize($pollOpt2Val) ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label class="form-label">विकल्प 3 (Option 3)</label>
                            <input type="text" name="poll_opt3" class="form-control" value="<?= sanitize($pollOpt3) ?>">
                        </div>
                        <div>
                            <label class="form-label">% वोट</label>
                            <input type="number" name="poll_opt3_val" class="form-control" value="<?= sanitize($pollOpt3Val) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">कुल प्रदर्शित वोट्स (Total Votes Count)</label>
                        <input type="text" name="poll_total_votes" class="form-control" value="<?= sanitize($pollTotalVotes) ?>">
                    </div>
                </div>
            </div>

        </div>

    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
