<?php
/**
 * Contact Us Page (contact.php)
 * Himachal News Portal - Khabar 24
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDBConnection();
$successMsg = '';
$errorMsg = '';

// Handle Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errorMsg = 'कृपया सभी अनिवार्य (*) फ़ील्ड भरें।';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'कृपया एक मान्य ईमेल पता दर्ज करें।';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO contacts (name, email, phone, subject, message, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                sanitize($name),
                sanitize($email),
                sanitize($phone),
                sanitize($subject),
                sanitize($message)
            ]);
            $successMsg = 'धन्यवाद! आपका संदेश सफलतापूर्वक भेज दिया गया है। हमारी संपादकीय टीम जल्द ही आपसे संपर्क करेगी।';
        } catch (PDOException $e) {
            $errorMsg = 'संदेश भेजने में तकनीकी समस्या आई। कृपया पुनः प्रयास करें।';
        }
    }
}

$pageTitle = 'संपर्क करें (Contact Us) - Khabar 24 हिमाचल न्यूज़';
$pageDescription = 'Khabar 24 न्यूज़ रूम और संपादकीय टीम से संपर्क करें। समाचार सुझाव, प्रेस विज्ञप्ति और विज्ञापन के लिए हमसे जुड़ें।';

require_once __DIR__ . '/includes/header.php';
?>

<main class="main-layout">
    <div class="container">
        
        <div class="static-page-card">
            <h1 class="static-page-title">संपर्क करें (Contact Us)</h1>
            <p style="color: var(--text-secondary); margin-bottom: 25px; font-size: 1.05rem;">
                समाचार सुझाव (News Tips), प्रेस विज्ञप्ति, विज्ञापन या किसी भी प्रतिक्रिया के लिए हमारी संपादकीय टीम से संपर्क करें।
            </p>

            <?php if (!empty($successMsg)): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle" style="margin-right: 6px;"></i> <?= $successMsg ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i> <?= $errorMsg ?>
                </div>
            <?php endif; ?>

            <div class="contact-grid">
                <!-- Contact Details Card -->
                <div class="contact-info-card">
                    <h3 style="font-size: 1.3rem; border-bottom: 1px solid var(--border-dark); padding-bottom: 12px; margin-bottom: 8px;">
                        मुख्यालय एवं न्यूज़ रूम
                    </h3>

                    <div class="contact-item">
                        <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <strong style="display: block; font-size: 1rem; color: #fff;">मुख्य कार्यालय:</strong>
                            <span style="color: #CBD5E0;"><?= sanitize(getSetting($pdo, 'contact_address', APP_ADDRESS)) ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <strong style="display: block; font-size: 1rem; color: #fff;">हेल्पलाइन / टेलीफोन:</strong>
                            <span style="color: #CBD5E0;"><?= sanitize(getSetting($pdo, 'contact_phone', APP_PHONE)) ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <strong style="display: block; font-size: 1rem; color: #fff;">संपादकीय ईमेल:</strong>
                            <span style="color: #CBD5E0;"><?= sanitize(getSetting($pdo, 'contact_email', APP_EMAIL)) ?></span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-item-icon"><i class="fas fa-bullhorn"></i></div>
                        <div>
                            <strong style="display: block; font-size: 1rem; color: #fff;">विज्ञापन एवं सहयोग:</strong>
                            <span style="color: #CBD5E0;"><?= sanitize(getSetting($pdo, 'contact_email', APP_EMAIL)) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Contact Form -->
                <div class="contact-form-card">
                    <form action="contact.php" method="POST">
                        <div class="form-group">
                            <label class="form-label" for="name">आपका पूरा नाम *</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="उदा. सुरेन्द्र ठाकुर" required value="<?= isset($_POST['name']) && empty($successMsg) ? sanitize($_POST['name']) : '' ?>">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label class="form-label" for="email">ईमेल पता *</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required value="<?= isset($_POST['email']) && empty($successMsg) ? sanitize($_POST['email']) : '' ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">मोबाइल / फोन नंबर</label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="+91 98XXX XXXXX" value="<?= isset($_POST['phone']) && empty($successMsg) ? sanitize($_POST['phone']) : '' ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">विषय (Subject) *</label>
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="उदा. समाचार सुझाव / विज्ञापन / प्रतिक्रिया" required value="<?= isset($_POST['subject']) && empty($successMsg) ? sanitize($_POST['subject']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">संदेश या खबर का विवरण *</label>
                            <textarea id="message" name="message" rows="5" class="form-control" placeholder="अपना विस्तृत संदेश यहां लिखें..." required><?= isset($_POST['message']) && empty($successMsg) ? sanitize($_POST['message']) : '' ?></textarea>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane"></i> संदेश भेजें
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
