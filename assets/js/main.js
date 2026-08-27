/**
 * News 24 Himachal - Main Frontend JavaScript
 * Live Clock, Category Filtering, Weather, Opinion Poll & Notifications
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Live Clock
    initLiveClock();

    // 2. Mobile Menu Toggle & Dropdowns
    initMobileNav();

    // 3. Copy Link Share Button
    initCopyLinkButton();

    // 4. Interactive Horoscope (Rashifal) Widget
    initHoroscopeWidget();

    // 5. Multi-City Himachal Weather Widget
    initWeatherWidget();

    // 6. News Subscription Modal & Header Status Manager
    initSubscriptionManager();

    // 7. Interactive Auto-Playing Slider for 'सबसे बड़ी खबर'
    initSabseBadiKhabarSlider();

    // 8. Interactive Hero Opinion Poll Widget
    initHeroOpinionPoll();
});

/**
 * Live Digital Clock in Indian Standard Time (IST)
 */
function initLiveClock() {
    const clockEl = document.getElementById('live-clock');
    if (!clockEl) return;

    function updateTime() {
        const now = new Date();
        const options = { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit', 
            hour12: true 
        };
        clockEl.textContent = now.toLocaleTimeString('en-US', options);
    }

    updateTime();
    setInterval(updateTime, 1000);
}

/**
 * Mobile Navigation Drawer & Submenu Accordion
 */
function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-nav-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (toggleBtn && navMenu) {
        toggleBtn.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                if (navMenu.classList.contains('open')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            }
        });
    }

    // Handle dropdown clicks on mobile devices
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        const dropdown = item.querySelector('.dropdown-menu');
        const link = item.querySelector('.nav-link');
        if (dropdown && link) {
            link.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    if (e.target.classList.contains('fa-angle-down') || !item.classList.contains('dropdown-open')) {
                        e.preventDefault();
                        item.classList.toggle('dropdown-open');
                    }
                }
            });
        }
    });
}

/**
 * Copy URL to Clipboard
 */
function initCopyLinkButton() {
    const copyBtns = document.querySelectorAll('.copy-share-btn');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> लिंक कॉपी हुआ!';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            } else {
                prompt('इस लिंक को कॉपी करें:', url);
            }
        });
    });
}

/**
 * Interactive Horoscope (12 Rashi Forecast Data & Switcher)
 */
function initHoroscopeWidget() {
    const rashiButtons = document.querySelectorAll('#rashi-selector .rashi-btn');
    if (!rashiButtons.length) return;

    const rashiData = {
        mesh: {
            name: '♈ मेष राशि (Aries)', element: 'अग्नि तत्व',
            text: 'आज आपका दिन उत्साह और सकारात्मक ऊर्जा से भरपूर रहेगा। कार्यक्षेत्र में पदोन्नति या नए प्रोजेक्ट के अवसर मिल सकते हैं। आर्थिक स्थिति सुदृढ़ होगी।',
            num: '9', color: 'लाल', colorCode: '#E50914', luck: '88%'
        },
        vrish: {
            name: '♉ वृषभ राशि (Taurus)', element: 'पृथ्वी तत्व',
            text: 'वित्तीय मामलों में आज सावधानी बरतें। पारिवारिक सदस्यों का पूर्ण सहयोग मिलेगा। व्यापार में नए संपर्क बनेंगे जो भविष्य में लाभकारी सिद्ध होंगे।',
            num: '6', color: 'सफेद', colorCode: '#64748B', luck: '79%'
        },
        mithun: {
            name: '♊ मिथुन राशि (Gemini)', element: 'वायु तत्व',
            text: 'संचार कौशल और बुद्धिमत्ता से कठिन कार्यों को भी सरलता से पूर्ण कर लेंगे। पुराने मित्रों से मुलाकात होगी। स्वास्थ्य उत्तम रहेगा।',
            num: '5', color: 'हरा', colorCode: '#10B981', luck: '92%'
        },
        kark: {
            name: '♋ कर्क राशि (Cancer)', element: 'जल तत्व',
            text: 'माता-पिता का आशीर्वाद लेकर दिन की शुरुआत करें। धार्मिक कार्यों में रुचि बढ़ेगी। नौकरीपेशा जातकों के लिए दिन अनुकूल रहेगा।',
            num: '2', color: 'चांदी', colorCode: '#94A3B8', luck: '84%'
        },
        singh: {
            name: '♌ सिंह राशि (Leo)', element: 'अग्नि तत्व',
            text: 'आत्मविश्वास में वृद्धि होगी। सामाजिक प्रतिष्ठा बढ़ेगी। सरकारी कार्यों में आ रही बाधाएं दूर होंगी। निवेश के लिए शुभ समय है।',
            num: '1', color: 'केसरिया', colorCode: '#F59E0B', luck: '95%'
        },
        kanya: {
            name: '♍ कन्या राशि (Virgo)', element: 'पृथ्वी तत्व',
            text: 'कार्यस्थल पर आपकी मेहनत की सराहना होगी। अनावश्यक खर्चों पर नियंत्रण रखें। विद्यार्थियों के लिए प्रतियोगिता परीक्षा में सफलता के योग हैं।',
            num: '7', color: 'गहरा हरा', colorCode: '#047857', luck: '81%'
        },
        tula: {
            name: '♎ तुला राशि (Libra)', element: 'वायु तत्व',
            text: 'दांपत्य जीवन में मधुरता रहेगी। कला और रचनात्मक क्षेत्रों से जुड़े लोगों को बड़ा मंच प्राप्त होगा। आर्थिक स्थिति संतोषजनक रहेगी।',
            num: '6', color: 'गुलाबी', colorCode: '#EC4899', luck: '86%'
        },
        vrishchik: {
            name: '♏ वृश्चिक राशि (Scorpio)', element: 'जल तत्व',
            text: 'साहस और पराक्रम में वृद्धि होगी। विरोधियों पर विजय प्राप्त होगी। भूमि या वाहन क्रय करने का विचार बन सकता है।',
            num: '8', color: 'मैरून', colorCode: '#991B1B', luck: '90%'
        },
        dhanu: {
            name: '♐ धनु राशि (Sagittarius)', element: 'अग्नि तत्व',
            text: 'उच्च शिक्षा और आध्यात्मिक ज्ञान में वृद्धि होगी। दूर की यात्रा के योग बन सकते हैं। बड़ों का मार्गदर्शन लाभकारी रहेगा।',
            num: '3', color: 'पीला', colorCode: '#EAB308', luck: '87%'
        },
        makar: {
            name: '♑ मकर राशि (Capricorn)', element: 'पृथ्वी तत्व',
            text: 'कठिन परिश्रम का उचित फल मिलेगा। उच्च अधिकारियों का विश्वास प्राप्त होगा। स्वास्थ्य के प्रति थोड़ी सतर्कता आवश्यक है।',
            num: '4', color: 'नीला', colorCode: '#2563EB', luck: '78%'
        },
        kumbh: {
            name: '♒ कुंभ राशि (Aquarius)', element: 'वायु तत्व',
            text: 'नवाचार और नए विचारों से व्यापार को गति मिलेगी। सामाजिक सेवा में योगदान देंगे। अचानक धन लाभ के योग बन रहे हैं।',
            num: '11', color: 'आसमानी', colorCode: '#0284C7', luck: '91%'
        },
        meen: {
            name: '♓ मीन राशि (Pisces)', element: 'जल तत्व',
            text: 'मानसिक शांति का अनुभव होगा। धर्म-कर्म और परोपकार में मन लगेगा। विदेश से शुभ समाचार मिलने की संभावना है।',
            num: '12', color: 'सुनहरा', colorCode: '#CA8A04', luck: '89%'
        }
    };

    const nameEl = document.getElementById('rashi-name');
    const elemEl = document.getElementById('rashi-element');
    const textEl = document.getElementById('rashi-text');
    const numEl = document.getElementById('rashi-number');
    const colorEl = document.getElementById('rashi-color');
    const luckEl = document.getElementById('rashi-luck');

    rashiButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            rashiButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const key = btn.getAttribute('data-rashi');
            const data = rashiData[key];
            if (!data) return;

            if (nameEl) nameEl.textContent = data.name;
            if (elemEl) elemEl.textContent = data.element;
            if (textEl) textEl.textContent = data.text;
            if (numEl) numEl.textContent = data.num;
            if (colorEl) {
                colorEl.textContent = data.color;
                colorEl.style.color = data.colorCode;
            }
            if (luckEl) luckEl.textContent = data.luck;
        });
    });
}

/**
 * Multi-City Himachal Weather Data & Switcher
 */
function initWeatherWidget() {
    const cityButtons = document.querySelectorAll('#weather-city-tabs .w-city-btn');
    if (!cityButtons.length) return;

    const weatherData = {
        shimla: {
            title: 'शिमला (राजधानी)', condition: 'धूप खिली, सुहावना मौसम',
            temp: '14°C', minmax: '6°C / 17°C', humidity: '45%', wind: '8 km/h',
            aqi: '32 (उत्कृष्ट)', tip: 'धूप खिली होने से सभी प्रमुख मार्ग एवं कुफरी-नारकंडा खुला है।',
            icon: '<i class="fas fa-sun" style="color:#F59E0B;"></i>'
        },
        dharamshala: {
            title: 'धर्मशाला / मैक्लोडगंज', condition: 'साफ आसमान, हल्की धूप',
            temp: '18°C', minmax: '10°C / 22°C', humidity: '52%', wind: '6 km/h',
            aqi: '28 (उत्कृष्ट)', tip: 'कांगड़ा घाटी में मौसम पर्यटन एवं पैराग्लाइडिंग के अनुकूल।',
            icon: '<i class="fas fa-cloud-sun" style="color:#F59E0B;"></i>'
        },
        manali: {
            title: 'मनाली (रोहतांग)', condition: 'ठंडी हवाएं, बर्फबारी के आसार',
            temp: '9°C', minmax: '1°C / 12°C', humidity: '68%', wind: '14 km/h',
            aqi: '18 (अति शुद्ध)', tip: 'अटल टनल और सोलांग नाला पर शाम को फिसलन संभव, संभलकर चलें।',
            icon: '<i class="fas fa-snowflake" style="color:#38BDF8;"></i>'
        },
        mandi: {
            title: 'मंडी (छोटी काशी)', condition: 'खिली धूप, सामान्य मौसम',
            temp: '16°C', minmax: '9°C / 21°C', humidity: '48%', wind: '7 km/h',
            aqi: '35 (अच्छा)', tip: 'कीरतपुर-मनाली फोरलेन पर यातायात पूर्ण रूप से सुचारू।',
            icon: '<i class="fas fa-sun" style="color:#F59E0B;"></i>'
        },
        solan: {
            title: 'सोलन (मशरूम सिटी)', condition: 'स्वच्छ वातावरण, धूप',
            temp: '15°C', minmax: '8°C / 20°C', humidity: '42%', wind: '9 km/h',
            aqi: '38 (अच्छा)', tip: 'कालका-शिमला हाईवे पर सामान्य गति से वाहन चलाएं।',
            icon: '<i class="fas fa-sun" style="color:#F59E0B;"></i>'
        },
        kullu: {
            title: 'कुल्लू (देव घाटी)', condition: 'हल्के बादल, सुहावनी ठंड',
            temp: '12°C', minmax: '4°C / 16°C', humidity: '55%', wind: '10 km/h',
            aqi: '22 (उत्कृष्ट)', tip: 'ब्यास नदी किनारे सुरक्षित स्थानों पर ही कैंपिंग करें।',
            icon: '<i class="fas fa-cloud" style="color:#94A3B8;"></i>'
        }
    };

    const titleEl = document.getElementById('w-city-title');
    const condEl = document.getElementById('w-condition');
    const tempEl = document.getElementById('w-temp');
    const minmaxEl = document.getElementById('w-minmax');
    const humEl = document.getElementById('w-humidity');
    const windEl = document.getElementById('w-wind');
    const aqiEl = document.getElementById('w-aqi');
    const tipEl = document.getElementById('w-tip');
    const iconEl = document.getElementById('w-icon');

    cityButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            cityButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const city = btn.getAttribute('data-city');
            const d = weatherData[city];
            if (!d) return;

            if (titleEl) titleEl.textContent = d.title;
            if (condEl) condEl.textContent = d.condition;
            if (tempEl) tempEl.textContent = d.temp;
            if (minmaxEl) minmaxEl.textContent = d.minmax;
            if (humEl) humEl.textContent = d.humidity;
            if (windEl) windEl.textContent = d.wind;
            if (aqiEl) aqiEl.textContent = d.aqi;
            if (tipEl) tipEl.innerHTML = '<i class="fas fa-car"></i> <strong>यात्रा सलाह:</strong> ' + d.tip;
            if (iconEl) iconEl.innerHTML = d.icon;
        });
    });
}

/**
/**
 * Device Fingerprint & Metadata Helper
 */
function getDeviceMetadata() {
    const ua = navigator.userAgent;
    let deviceType = 'Desktop';
    let os = 'Unknown OS';
    let browser = 'Unknown Browser';

    // Device Type
    if (/Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua)) {
        deviceType = /iPad|Tablet/i.test(ua) ? 'Tablet' : 'Mobile';
    }

    // OS
    if (/Windows NT 10.0/i.test(ua)) os = 'Windows 10/11';
    else if (/Windows NT 6.3/i.test(ua)) os = 'Windows 8.1';
    else if (/Windows NT 6.1/i.test(ua)) os = 'Windows 7';
    else if (/Android/i.test(ua)) os = 'Android';
    else if (/iPhone|iPad|iPod/i.test(ua)) os = 'iOS';
    else if (/Mac OS X/i.test(ua)) os = 'macOS';
    else if (/Linux/i.test(ua)) os = 'Linux';

    // Browser
    if (/Edg\//i.test(ua)) browser = 'Edge';
    else if (/Chrome\//i.test(ua)) browser = 'Chrome';
    else if (/Firefox\//i.test(ua)) browser = 'Firefox';
    else if (/Safari\//i.test(ua) && !/Chrome\//i.test(ua)) browser = 'Safari';
    else if (/OPR\//i.test(ua) || /Opera/i.test(ua)) browser = 'Opera';

    const deviceName = `${os} (${browser})`;

    return { deviceType, os, browser, deviceName };
}

/**
 * Get or Generate Unique Persistent Device ID
 */
function getOrCreateDeviceId() {
    const DEVICE_ID_KEY = 'himachal_news_device_id';
    let deviceId = localStorage.getItem(DEVICE_ID_KEY);
    if (!deviceId) {
        const randHex = () => Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1).toUpperCase();
        deviceId = 'DEV-' + Date.now().toString(36).toUpperCase() + '-' + randHex() + '-' + randHex();
        localStorage.setItem(DEVICE_ID_KEY, deviceId);
    }
    return deviceId;
}

/**
 * News Subscription Popup Modal & Dynamic Button State Manager (1-Click Device Recognition)
 */
function initSubscriptionManager() {
    const STORAGE_KEY = 'himachal_news_subscribed';
    const SESSION_DISMISSED_KEY = 'himachal_news_popup_dismissed';

    const subModal = document.getElementById('news-subscribe-modal');
    const alreadyModal = document.getElementById('already-subscribed-modal');
    const modalView = document.getElementById('news-subscribe-view');
    const modalSuccess = document.getElementById('modal-success-message');
    const modalSubmitBtn = document.getElementById('modal-submit-btn');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const modalSkipBtn = document.getElementById('modal-skip-btn');
    const successDoneBtn = document.getElementById('modal-success-done-btn');
    const alreadyModalCloseBtn = document.getElementById('already-modal-close-btn');
    const alreadyModalDoneBtn = document.getElementById('already-modal-done-btn');
    const unsubscribeBtn = document.getElementById('btn-unsubscribe');

    // Dynamic Device Elements
    const modalDeviceIcon = document.getElementById('modal-device-icon');
    const modalDeviceName = document.getElementById('modal-device-name');
    const modalDeviceIdPreview = document.getElementById('modal-device-id-preview');
    const alreadySubDevice = document.getElementById('already-subscribed-device');
    const alreadySubPlatform = document.getElementById('already-subscribed-platform');

    const deviceId = getOrCreateDeviceId();
    const meta = getDeviceMetadata();

    // Populate Device info in modal
    if (modalDeviceIdPreview) modalDeviceIdPreview.textContent = deviceId;
    if (modalDeviceName) modalDeviceName.textContent = `पहचाना गया: ${meta.deviceName}`;
    if (modalDeviceIcon) {
        modalDeviceIcon.innerHTML = meta.deviceType === 'Mobile' 
            ? '<i class="fas fa-mobile-alt"></i>' 
            : '<i class="fas fa-laptop"></i>';
    }

    // Function to check subscription state
    function isSubscribed() {
        return localStorage.getItem(STORAGE_KEY) === 'true';
    }

    // Update all subscribe buttons on the page
    function updateSubscribeButtons() {
        const subbed = isSubscribed();
        const btns = document.querySelectorAll('.global-subscribe-btn');

        btns.forEach(btn => {
            if (subbed) {
                btn.classList.remove('not-subscribed');
                btn.classList.add('is-subscribed');
                btn.innerHTML = '<i class="fas fa-check-circle"></i> <span class="btn-text">SUBSCRIBED</span>';
                btn.setAttribute('title', 'यह डिवाइस पहले से सब्सक्राइब है (विवरण देखने के लिए क्लिक करें)');
            } else {
                btn.classList.remove('is-subscribed');
                btn.classList.add('not-subscribed');
                btn.innerHTML = '<i class="fas fa-bell"></i> <span class="btn-text">SUBSCRIBE</span>';
                btn.setAttribute('title', '1-Click में हिमाचल की ताज़ा खबरों के लिए नोटिफिकेशन चालू करें');
            }
        });
    }

    // Open Subscribe Modal
    function openSubscribeModal() {
        if (!subModal) return;
        if (modalView) modalView.style.display = 'block';
        if (modalSuccess) modalSuccess.style.display = 'none';
        subModal.classList.add('active');
        subModal.setAttribute('aria-hidden', 'false');
    }

    // Close Subscribe Modal
    function closeSubscribeModal() {
        if (!subModal) return;
        subModal.classList.remove('active');
        subModal.setAttribute('aria-hidden', 'true');
    }

    // Open Already Subscribed Modal
    function openAlreadyModal() {
        if (!alreadyModal) return;
        if (alreadySubDevice) alreadySubDevice.textContent = deviceId;
        if (alreadySubPlatform) alreadySubPlatform.textContent = `${meta.deviceName} • Status: Active`;
        alreadyModal.classList.add('active');
        alreadyModal.setAttribute('aria-hidden', 'false');
    }

    // Close Already Subscribed Modal
    function closeAlreadyModal() {
        if (!alreadyModal) return;
        alreadyModal.classList.remove('active');
        alreadyModal.setAttribute('aria-hidden', 'true');
    }

    // Handle 1-Click Subscription Process
    async function handleSubscribe() {
        if (!modalSubmitBtn) return;
        modalSubmitBtn.disabled = true;
        modalSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> डिवाइस रजिस्टर हो रहा है...';

        // 1. Request Browser Notification Permission if supported
        if ('Notification' in window && Notification.permission === 'default') {
            try {
                await Notification.requestPermission();
            } catch (err) {
                console.log('Notification permission request bypassed:', err);
            }
        }

        // 2. Call backend subscribe API
        try {
            const payload = {
                action: 'subscribe',
                device_id: deviceId,
                device_type: meta.deviceType,
                device_name: meta.deviceName,
                browser: meta.browser,
                os: meta.os
            };

            const res = await fetch('api/subscribe.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            localStorage.setItem(STORAGE_KEY, 'true');
            updateSubscribeButtons();

            if (modalView) modalView.style.display = 'none';
            if (modalSuccess) modalSuccess.style.display = 'block';

            // Auto close after 2.5 seconds
            setTimeout(() => {
                if (subModal && subModal.classList.contains('active')) {
                    closeSubscribeModal();
                }
            }, 2500);

        } catch (err) {
            console.error('Subscription error:', err);
            // Fallback store locally
            localStorage.setItem(STORAGE_KEY, 'true');
            updateSubscribeButtons();
            if (modalView) modalView.style.display = 'none';
            if (modalSuccess) modalSuccess.style.display = 'block';
        } finally {
            modalSubmitBtn.disabled = false;
            modalSubmitBtn.innerHTML = '<i class="fas fa-bell"></i> अभी सब्सक्राइब करें (Enable Notifications)';
        }
    }

    // Handle Unsubscribe Process
    async function handleUnsubscribe() {
        if (confirm('क्या आप सच में इस डिवाइस पर न्यूज़ नोटिफिकेशन बंद करना चाहते हैं?')) {
            try {
                await fetch('api/subscribe.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'unsubscribe',
                        device_id: deviceId
                    })
                });
            } catch (err) {
                console.error('Unsubscribe error:', err);
            }

            localStorage.removeItem(STORAGE_KEY);
            updateSubscribeButtons();
            closeAlreadyModal();
            alert('यह डिवाइस अनसब्सक्राइब हो चुका है। आप कभी भी फिर से 1-क्लिक में सब्सक्राइब कर सकते हैं।');
        }
    }

    // Event Listeners
    if (modalSubmitBtn) {
        modalSubmitBtn.addEventListener('click', handleSubscribe);
    }

    if (unsubscribeBtn) {
        unsubscribeBtn.addEventListener('click', handleUnsubscribe);
    }

    // Handle button click for any subscribe button on page
    document.addEventListener('click', (e) => {
        const targetBtn = e.target.closest('.global-subscribe-btn');
        if (targetBtn) {
            e.preventDefault();
            if (isSubscribed()) {
                openAlreadyModal();
            } else {
                openSubscribeModal();
            }
        }
    });

    // Close buttons handlers
    if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', () => {
            sessionStorage.setItem(SESSION_DISMISSED_KEY, 'true');
            closeSubscribeModal();
        });
    }

    if (modalSkipBtn) {
        modalSkipBtn.addEventListener('click', () => {
            sessionStorage.setItem(SESSION_DISMISSED_KEY, 'true');
            closeSubscribeModal();
        });
    }

    if (successDoneBtn) {
        successDoneBtn.addEventListener('click', closeSubscribeModal);
    }

    if (alreadyModalCloseBtn) {
        alreadyModalCloseBtn.addEventListener('click', closeAlreadyModal);
    }

    if (alreadyModalDoneBtn) {
        alreadyModalDoneBtn.addEventListener('click', closeAlreadyModal);
    }

    // Close on overlay backdrop click
    if (subModal) {
        subModal.addEventListener('click', (e) => {
            if (e.target === subModal) {
                sessionStorage.setItem(SESSION_DISMISSED_KEY, 'true');
                closeSubscribeModal();
            }
        });
    }

    if (alreadyModal) {
        alreadyModal.addEventListener('click', (e) => {
            if (e.target === alreadyModal) {
                closeAlreadyModal();
            }
        });
    }

    // Handle ESC key to close modals
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (subModal && subModal.classList.contains('active')) {
                sessionStorage.setItem(SESSION_DISMISSED_KEY, 'true');
                closeSubscribeModal();
            }
            if (alreadyModal && alreadyModal.classList.contains('active')) {
                closeAlreadyModal();
            }
        }
    });

    // Initial Button State Check
    updateSubscribeButtons();

    // Automatic Popup Trigger for First Time Visitors
    if (!isSubscribed()) {
        const isDismissed = sessionStorage.getItem(SESSION_DISMISSED_KEY);
        if (!isDismissed) {
            setTimeout(() => {
                if (!isSubscribed()) {
                    openSubscribeModal();
                }
            }, 1500);
        }
    }

    // Initialize Manual Push Notification Client Listener
    initLivePushNotificationSync(deviceId);
}

/**
 * Client-Side Manual Notification Sync & Toast Dispatcher
 * Subscribed devices receive alerts ONLY when sent manually by Admin
 */
function initLivePushNotificationSync(deviceId) {
    const STORAGE_KEY = 'himachal_news_subscribed';
    const toastContainer = document.getElementById('floating-push-toast-container');

    async function checkNewNotifications() {
        if (localStorage.getItem(STORAGE_KEY) !== 'true') return;

        try {
            const res = await fetch(`api/notifications.php?action=check_new&device_id=${encodeURIComponent(deviceId)}`);
            if (!res.ok) return;
            const data = await res.json();

            if (data.success && data.has_new && Array.isArray(data.notifications)) {
                data.notifications.forEach(notif => {
                    displayPushNotification(notif);
                });
            }
        } catch (err) {
            // Silently handle network polling error
        }
    }

    function displayPushNotification(notif) {
        const title = notif.title || 'News 24 Himachal अलर्ट';
        const message = notif.message || '';
        const url = notif.url || 'http://localhost:8000';
        const badge = notif.badge_text || 'ताज़ा खबर';
        const img = notif.image_url || 'assets/images/logo.png';

        // 1. Browser Native Web Notification (if permission granted)
        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                const nativeNotif = new Notification(`${badge}: ${title}`, {
                    body: message,
                    icon: img.startsWith('http') || img.startsWith('/') ? img : `/${img}`,
                    tag: `news24-himachal-${notif.id}`,
                    data: { url: url }
                });

                nativeNotif.onclick = function() {
                    window.focus();
                    window.location.href = url;
                    // Track click
                    fetch(`api/notifications.php?action=track_click&device_id=${encodeURIComponent(deviceId)}&notification_id=${notif.id}`);
                };
            } catch (e) {
                console.warn('Native notification display error:', e);
            }
        }

        // 2. In-Page Rich Floating Toast Banner
        if (toastContainer) {
            const toast = document.createElement('a');
            toast.href = url;
            toast.className = 'floating-push-toast';
            toast.setAttribute('target', '_self');

            toast.innerHTML = `
                <div class="toast-icon-wrap">
                    ${img && (img.startsWith('http') || img.startsWith('assets/')) 
                        ? `<img src="${img}" alt="Alert" onerror="this.parentElement.innerHTML='<i class=\\'fas fa-bell\\'></i>'">` 
                        : '<i class="fas fa-bell"></i>'}
                </div>
                <div class="toast-content-wrap">
                    <div class="toast-header-row">
                        <span class="toast-badge">${badge}</span>
                        <button type="button" class="toast-close-btn" aria-label="Close">&times;</button>
                    </div>
                    <div class="toast-title">${title}</div>
                    <div class="toast-desc">${message}</div>
                    <div class="toast-action-link">पूरी खबर पढ़ें <i class="fas fa-arrow-right"></i></div>
                </div>
            `;

            // Track click when clicked
            toast.addEventListener('click', (e) => {
                if (e.target.closest('.toast-close-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    toast.remove();
                    return;
                }
                fetch(`api/notifications.php?action=track_click&device_id=${encodeURIComponent(deviceId)}&notification_id=${notif.id}`);
            });

            const closeBtn = toast.querySelector('.toast-close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    toast.remove();
                });
            }

            toastContainer.appendChild(toast);

            // Auto dismiss toast after 9 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100px)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 9000);
        }
    }

    // Check on startup
    checkNewNotifications();

    // Check periodically every 25 seconds
    setInterval(checkNewNotifications, 25000);
}

/**
 * Interactive Auto-Playing Carousel for "सबसे बड़ी खबर" (Top 3 Sub-category Slider)
 */
function initSabseBadiKhabarSlider() {
    const sliderContainer = document.getElementById('sabseBadiKhabarSlider');
    if (!sliderContainer) return;

    const slides = sliderContainer.querySelectorAll('.lead-slide');
    const dots = sliderContainer.querySelectorAll('.lead-dot');
    const prevBtn = document.getElementById('leadPrevBtn');
    const nextBtn = document.getElementById('leadNextBtn');
    const counterCurrent = document.getElementById('leadSlideCurrent');

    if (!slides || slides.length <= 1) return;

    let currentIndex = 0;
    const totalSlides = slides.length;
    const intervalTime = parseInt(sliderContainer.getAttribute('data-autoplay-interval'), 10) || 4500;
    let autoPlayTimer = null;
    let isHovered = false;

    function showSlide(index) {
        if (index >= totalSlides) index = 0;
        if (index < 0) index = totalSlides - 1;

        currentIndex = index;

        slides.forEach((slide, i) => {
            if (i === currentIndex) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });

        dots.forEach((dot, i) => {
            if (i === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        if (counterCurrent) {
            counterCurrent.textContent = (currentIndex + 1);
        }
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function prevSlide() {
        showSlide(currentIndex - 1);
    }

    function startAutoPlay() {
        if (autoPlayTimer) clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(() => {
            if (!isHovered) {
                nextSlide();
            }
        }, intervalTime);
    }

    function pauseAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    // Button controls
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            nextSlide();
            startAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            prevSlide();
            startAutoPlay();
        });
    }

    // Dots controls
    dots.forEach((dot, i) => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            showSlide(i);
            startAutoPlay();
        });
    });

    // Pause on hover
    const parentContainer = sliderContainer.closest('.lead-col-container') || sliderContainer;
    parentContainer.addEventListener('mouseenter', () => {
        isHovered = true;
        pauseAutoPlay();
    });

    parentContainer.addEventListener('mouseleave', () => {
        isHovered = false;
        startAutoPlay();
    });

    // Touch Swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    sliderContainer.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const threshold = 40;
        if (touchEndX < touchStartX - threshold) {
            nextSlide();
            startAutoPlay();
        } else if (touchEndX > touchStartX + threshold) {
            prevSlide();
            startAutoPlay();
        }
    }

    // Start initial autoplay
    startAutoPlay();
}

/**
 * Interactive Hero Opinion Poll Widget (1 Question, 3 Options with %, Compact Height)
 */
function initHeroOpinionPoll() {
    const pollCard = document.getElementById('heroPollCard');
    if (!pollCard) return;

    const optButtons = pollCard.querySelectorAll('.poll-opt-btn');
    const totalVotesEl = document.getElementById('pollTotalVotes');
    const storageKey = 'himachal_news_hero_poll_voted';

    // Check if previously voted
    const savedVote = localStorage.getItem(storageKey);
    if (savedVote) {
        optButtons.forEach(btn => {
            if (btn.getAttribute('data-opt') === savedVote) {
                btn.classList.add('voted');
            }
        });
    }

    optButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const optType = btn.getAttribute('data-opt');

            optButtons.forEach(b => b.classList.remove('voted'));
            btn.classList.add('voted');
            localStorage.setItem(storageKey, optType);

            // Increment votes counter dynamically
            if (totalVotesEl && !savedVote) {
                totalVotesEl.textContent = '2,841';
            }

            // Quick haptic micro-animation
            btn.style.transform = 'scale(0.98)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 140);
        });
    });
}


