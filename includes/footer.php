<?php
/**
 * Footer Component
 * News 24 Himachal
 */
?>
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: About Portal -->
                <div class="footer-col footer-about">
                    <div style="margin-bottom: 16px;">
                        <img src="assets/images/logo.png" alt="News 24 Himachal" style="max-width: 192px; height: auto; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
                    </div>
                    <p>देवभूमि हिमाचल प्रदेश का सबसे विश्वसनीय, निष्पक्ष और अग्रणी डिजिटल समाचार पोर्टल। शिमला से लेकर चंबा तक हर खबर सबसे पहले आप तक।</p>
                    <div style="font-size: 0.85rem; color: #A0AEC0; display: flex; flex-direction: column; gap: 6px;">
                        <div><i class="fas fa-map-marker-alt" style="color: var(--primary-red); margin-right: 6px;"></i> <?= sanitize(getSetting($pdo, 'contact_address', APP_ADDRESS)) ?></div>
                        <div><i class="fas fa-phone-alt" style="color: var(--primary-red); margin-right: 6px;"></i> <?= sanitize(getSetting($pdo, 'contact_phone', APP_PHONE)) ?></div>
                        <div><i class="fas fa-envelope" style="color: var(--primary-red); margin-right: 6px;"></i> <?= sanitize(getSetting($pdo, 'contact_email', APP_EMAIL)) ?></div>
                    </div>
                </div>

                <!-- Column 2: Quick Categories (Exact Match to Home Navigation Bar - 2 Columns) -->
                <div class="footer-col footer-links">
                    <h3 class="footer-col-title">मुख्य श्रेणियां</h3>
                    <ul class="footer-2col-list">
                        <li><a href="category.php?cat=breaking-news"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> ब्रेकिंग न्यूज़</a></li>
                        <li><a href="category.php?cat=rajniti"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> राजनीति</a></li>
                        <li><a href="category.php?cat=himachal-darshan"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> हिमाचल दर्शन</a></li>
                        <li><a href="category.php?cat=manoranjan"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> मनोरंजन</a></li>
                        <li><a href="category.php?cat=khel"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> खेल</a></li>
                        <li><a href="category.php?cat=rashiphal"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> राशिफल</a></li>
                        <li><a href="category.php?cat=crime"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> क्राइम</a></li>
                        <li><a href="category.php?cat=desh"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> देश</a></li>
                        <li><a href="category.php?cat=duniya"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> दुनिया</a></li>
                    </ul>
                </div>

                <!-- Column 3: Legal & Quick Links -->
                <div class="footer-col footer-links">
                    <h3 class="footer-col-title">उपयोगी लिंक्स</h3>
                    <ul>
                        <li><a href="about.php"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> हमारे बारे में (About Us)</a></li>
                        <li><a href="contact.php"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> संपर्क करें (Contact Us)</a></li>
                        <li><a href="disclaimer.php"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> अस्वीकरण (Disclaimer)</a></li>
                        <li><a href="privacy-policy.php"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> गोपनीयता नीति (Privacy)</a></li>
                        <li><a href="terms.php"><i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> नियम व शर्तें (Terms)</a></li>
                    </ul>
                </div>

                <!-- Column 4: Follow Us & Connect -->
                <div class="footer-col">
                    <h3 class="footer-col-title">सोशल मीडिया से जुड़ें</h3>
                    <p style="font-size: 0.88rem; margin-bottom: 14px;">ताज़ा अपडेट्स और वीडियो बुलेटिन के लिए हमारे आधिकारिक सोशल मीडिया हैंडल्स को फॉलो करें।</p>
                    <div class="footer-socials">
                        <a href="<?= sanitize(getSetting($pdo, 'social_facebook', '#')) ?>" target="_blank" class="footer-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?= sanitize(getSetting($pdo, 'social_twitter', '#')) ?>" target="_blank" class="footer-social-btn" title="Twitter"><i class="fab fa-x-twitter"></i></a>
                        <a href="<?= sanitize(getSetting($pdo, 'social_youtube', '#')) ?>" target="_blank" class="footer-social-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="<?= sanitize(getSetting($pdo, 'social_instagram', '#')) ?>" target="_blank" class="footer-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="<?= sanitize(getSetting($pdo, 'social_telegram', '#')) ?>" target="_blank" class="footer-social-btn" title="Telegram"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner" style="justify-content: center; text-align: center;">
                <div style="text-align: center; width: 100%; font-size: 0.92rem; color: #94A3B8; font-weight: 500; letter-spacing: 0.3px;">
                    &copy; <?= date('Y') ?> all rights reserved with News 24 Himachal.
                </div>
            </div>
        </div>
    </footer>

    <!-- Fixed Bottom Sticky Breaking News Ticker -->
    <?php require_once __DIR__ . '/ticker.php'; ?>

    <!-- News Subscribe Modal Popup (1-Click Device Recognition - No Email/Phone Required) -->
    <div id="news-subscribe-modal" class="news-modal-overlay" aria-hidden="true" role="dialog">
        <div class="news-modal-card">
            <button type="button" class="news-modal-close" id="modal-close-btn" aria-label="Close Modal">&times;</button>
            
            <div class="news-modal-header">
                <div class="modal-badge"><i class="fas fa-bell"></i> 1-क्लिक न्यूज़ अलर्ट</div>
                <h2 class="modal-title">News 24 Himachal लाइव अपडेट्स</h2>
                <p class="modal-desc">देवभूमि हिमाचल प्रदेश की हर ब्रेकिंग न्यूज़, मौसम अलर्ट और सरकारी आदेश सीधे अपने डिवाइस पर पाएं।</p>
            </div>

            <div class="news-modal-body">
                <div id="news-subscribe-view">
                    <!-- Device Recognition Banner -->
                    <div class="device-detect-card">
                        <div class="device-icon-box" id="modal-device-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="device-info-box">
                            <div class="device-name-text" id="modal-device-name">पहचाना गया डिवाइस: Windows PC</div>
                            <div class="device-id-text">
                                <i class="fas fa-fingerprint"></i> Device ID: <span id="modal-device-id-preview">DEV-AUTO-GEN</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-features-list" style="margin: 18px 0;">
                        <div class="feature-item"><i class="fas fa-bolt" style="color: #E50914;"></i> कोई ईमेल या फ़ोन नंबर की ज़रूरत नहीं</div>
                        <div class="feature-item"><i class="fas fa-shield-alt" style="color: #10B981;"></i> केवल एडमिन द्वारा भेजी गई महत्वपूर्ण खबरें (नो स्पैम)</div>
                        <div class="feature-item"><i class="fas fa-bell" style="color: #F59E0B;"></i> मौसम, बर्फबारी और सड़क/ट्रैफिक लाइव एडवाइजरी</div>
                    </div>

                    <button type="button" id="modal-submit-btn" class="modal-submit-btn">
                        <i class="fas fa-bell"></i> अभी सब्सक्राइब करें (Enable Notifications)
                    </button>
                </div>

                <div id="modal-success-message" class="modal-success-state" style="display: none;">
                    <div class="success-icon-wrap">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>बधाई हो! आपका डिवाइस सब्सक्राइब हो चुका है</h3>
                    <p>अब आपको News 24 Himachal के महत्वपूर्ण और ब्रेकिंग अलर्ट सीधे इस स्क्रीन पर मिलते रहेंगे।</p>
                    <button type="button" class="modal-success-close-btn" id="modal-success-done-btn">खबरें पढ़ें</button>
                </div>
            </div>

            <div class="news-modal-footer">
                <button type="button" class="modal-skip-btn" id="modal-skip-btn">बाद में याद दिलाएं (Skip)</button>
            </div>
        </div>
    </div>

    <!-- Already Subscribed Info Modal -->
    <div id="already-subscribed-modal" class="news-modal-overlay" aria-hidden="true" role="dialog">
        <div class="news-modal-card info-card">
            <button type="button" class="news-modal-close" id="already-modal-close-btn" aria-label="Close Modal">&times;</button>
            <div class="news-modal-header info-header">
                <div class="modal-badge-green"><i class="fas fa-shield-alt"></i> सक्रिय सब्सक्रिप्शन</div>
                <h2 class="modal-title">आपका डिवाइस पहले से सब्सक्राइब है!</h2>
                <p class="modal-desc">News 24 Himachal से जुड़े रहने के लिए धन्यवाद। आपको महत्वपूर्ण लाइव अलर्ट भेजे जा रहे हैं।</p>
            </div>
            <div class="news-modal-body text-center">
                <div class="subscribed-status-box">
                    <i class="fas fa-check-circle status-green-icon"></i>
                    <div class="status-email-label">पहचाना गया डिवाइस एवं ID:</div>
                    <div class="status-email-val" id="already-subscribed-device" style="font-family: monospace; font-size: 0.95rem;">DEV-...</div>
                    <div id="already-subscribed-platform" style="font-size: 0.85rem; color: #64748B; margin-top: 4px;">Desktop Browser</div>
                </div>
                <div style="margin-top: 20px; display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="modal-success-close-btn" id="already-modal-done-btn" style="margin: 0; padding: 10px 24px;">ठीक है (OK)</button>
                    <button type="button" class="btn-unsubscribe" id="btn-unsubscribe">अनसब्सक्राइब करें (Unsubscribe)</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Live Push Notification Banner Container -->
    <div id="floating-push-toast-container" class="floating-push-toast-container" aria-live="polite"></div>

    <!-- Main JavaScript File -->
    <script src="assets/js/main.js"></script>
</body>
</html>
