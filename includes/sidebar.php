<?php
/**
 * Sidebar Component
 * Live Cricket Score, Interactive Horoscope (Rashifal), Multi-City Himachal Weather, Popular News, Newsletter
 */
$popularArticles = getPopularNews($pdo, 5);
$recentArticles = getRecentNews($pdo, 4);
?>
<aside class="sidebar">

    <!-- 1. Live Cricket Score Widget -->
    <div class="widget cricket-score-widget">
        <div class="widget-header" style="background: linear-gradient(135deg, #1E3A8A, #172554); border-bottom-color: #3B82F6;">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span><i class="fas fa-baseball-bat-ball" style="color: #60A5FA;"></i> लाइव क्रिकेट स्कोर</span>
                <span class="live-pulse-badge"><span class="pulse-dot"></span> LIVE</span>
            </div>
        </div>
        <div class="widget-body cricket-card-body">
            <div class="cricket-match-meta">
                <span>T20 इंटरनेशनल सीरीज • धर्मशाला</span>
                <span class="badge-series">मैच 3</span>
            </div>
            
            <div class="cricket-teams-row">
                <!-- Team 1: Australia -->
                <div class="cricket-team">
                    <div class="team-flag-box">🇦🇺</div>
                    <div class="team-name">ऑस्ट्रेलिया</div>
                    <div class="team-score">194/7 <small>(20.0)</small></div>
                </div>

                <div class="cricket-vs">VS</div>

                <!-- Team 2: India -->
                <div class="cricket-team active-team">
                    <div class="team-flag-box">🇮🇳</div>
                    <div class="team-name">भारत</div>
                    <div class="team-score highlight">182/3 <small>(17.4)</small></div>
                </div>
            </div>

            <div class="cricket-target-status">
                <i class="fas fa-flag-checkered"></i> भारत को जीत के लिए <strong>14 गेंदों में 13 रन</strong> चाहिए (RRR: 5.57)
            </div>

            <!-- Mini Live Batsman/Bowler Box -->
            <div class="cricket-mini-stats">
                <div class="stat-player">
                    <span><i class="fas fa-bat"></i> <strong>विराट कोहली*</strong></span>
                    <span><strong>76</strong> (42b, 4x6, 6x4)</span>
                </div>
                <div class="stat-player">
                    <span><strong>हार्दिक पांड्या</strong></span>
                    <span><strong>32</strong> (14b, 3x6)</span>
                </div>
                <div class="stat-player bowler-stat">
                    <span><i class="fas fa-baseball"></i> पैट कमिंस</span>
                    <span>3.4 ov • 1/34</span>
                </div>
            </div>

            <div class="cricket-footer-action">
                <a href="category.php?cat=sports" class="cricket-full-card-btn">
                    फुल स्कोरकार्ड एवं लाइव कमेंट्री &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Interactive Horoscope Widget (आज का राशिफल) -->
    <div class="widget horoscope-widget">
        <div class="widget-header" style="background: linear-gradient(135deg, #4C1D95, #312E81); border-bottom-color: #A855F7;">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span><i class="fas fa-sun" style="color: #FACC15;"></i> आज का राशिफल</span>
                <span style="font-size: 0.78rem; color: #E9D5FF; font-weight: 500;"><?= date('d M Y') ?></span>
            </div>
        </div>
        <div class="widget-body" style="padding: 16px;">
            <!-- Rashi Quick Selector Grid -->
            <div class="rashi-selector-tabs" id="rashi-selector">
                <button type="button" class="rashi-btn active" data-rashi="mesh" title="मेष">♈ मेष</button>
                <button type="button" class="rashi-btn" data-rashi="vrish" title="वृषभ">♉ वृष</button>
                <button type="button" class="rashi-btn" data-rashi="mithun" title="मिथुन">♊ मिथुन</button>
                <button type="button" class="rashi-btn" data-rashi="kark" title="कर्क">♋ कर्क</button>
                <button type="button" class="rashi-btn" data-rashi="singh" title="सिंह">♌ सिंह</button>
                <button type="button" class="rashi-btn" data-rashi="kanya" title="कन्या">♍ कन्या</button>
                <button type="button" class="rashi-btn" data-rashi="tula" title="तुला">♎ तुला</button>
                <button type="button" class="rashi-btn" data-rashi="vrishchik" title="वृश्चिक">♏ वृश्चिक</button>
                <button type="button" class="rashi-btn" data-rashi="dhanu" title="धनु">♐ धनु</button>
                <button type="button" class="rashi-btn" data-rashi="makar" title="मकर">♑ मकर</button>
                <button type="button" class="rashi-btn" data-rashi="kumbh" title="कुंभ">♒ कुंभ</button>
                <button type="button" class="rashi-btn" data-rashi="meen" title="मीन">♓ मीन</button>
            </div>

            <!-- Active Rashi Content Display Card -->
            <div class="rashi-display-card" id="rashi-display-content">
                <div class="rashi-title-row">
                    <h4 id="rashi-name" style="font-size: 1.15rem; color: #4C1D95; font-weight: 700;">♈ मेष राशि (Aries)</h4>
                    <span class="rashi-element-badge" id="rashi-element">अग्नि तत्व</span>
                </div>
                <p class="rashi-forecast-text" id="rashi-text">
                    आज आपका दिन उत्साह और सकारात्मक ऊर्जा से भरपूर रहेगा। कार्यक्षेत्र में पदोन्नति या नए प्रोजेक्ट के अवसर मिल सकते हैं। आर्थिक स्थिति सुदृढ़ होगी। पारिवारिक जीवन में मधुरता बनी रहेगी।
                </p>
                <div class="rashi-lucky-row">
                    <div class="lucky-pill">
                        <span class="label">शुभ अंक:</span>
                        <strong id="rashi-number">9</strong>
                    </div>
                    <div class="lucky-pill">
                        <span class="label">शुभ रंग:</span>
                        <strong id="rashi-color" style="color: #E50914;">लाल</strong>
                    </div>
                    <div class="lucky-pill">
                        <span class="label">भाग्य प्रतिशत:</span>
                        <strong id="rashi-luck">88%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Multi-City Himachal Weather Widget -->
    <div class="widget weather-multi-widget">
        <div class="widget-header" style="background: linear-gradient(135deg, #0F766E, #115E59); border-bottom-color: #2DD4BF;">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span><i class="fas fa-cloud-sun" style="color: #FDE047;"></i> हिमाचल मौसम अपडेट</span>
                <span style="font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 2px 7px; border-radius: 12px;">लाइव</span>
            </div>
        </div>
        <div class="widget-body" style="padding: 16px;">
            <!-- City Selection Tabs -->
            <div class="weather-city-tabs" id="weather-city-tabs">
                <button type="button" class="w-city-btn active" data-city="shimla">शिमला</button>
                <button type="button" class="w-city-btn" data-city="dharamshala">धर्मशाला</button>
                <button type="button" class="w-city-btn" data-city="manali">मनाली</button>
                <button type="button" class="w-city-btn" data-city="mandi">मंडी</button>
                <button type="button" class="w-city-btn" data-city="solan">सोलन</button>
                <button type="button" class="w-city-btn" data-city="kullu">कुल्लू</button>
            </div>

            <!-- Weather Details Card -->
            <div class="weather-details-card" id="weather-details-card">
                <div class="weather-main-row">
                    <div>
                        <div class="weather-city-name" id="w-city-title">शिमला (राजधानी)</div>
                        <div class="weather-condition" id="w-condition">धूप खिली, सुहावना मौसम</div>
                    </div>
                    <div class="weather-temp-wrap">
                        <div class="weather-icon-lg" id="w-icon"><i class="fas fa-sun"></i></div>
                        <div class="weather-temp-num" id="w-temp">14°C</div>
                    </div>
                </div>

                <div class="weather-metrics-grid">
                    <div class="w-metric-item">
                        <i class="fas fa-temperature-arrow-down"></i>
                        <div>
                            <span>न्यूनतम / अधिकतम</span>
                            <strong id="w-minmax">6°C / 17°C</strong>
                        </div>
                    </div>
                    <div class="w-metric-item">
                        <i class="fas fa-droplet"></i>
                        <div>
                            <span>नमी (Humidity)</span>
                            <strong id="w-humidity">45%</strong>
                        </div>
                    </div>
                    <div class="w-metric-item">
                        <i class="fas fa-wind"></i>
                        <div>
                            <span>हवा की गति</span>
                            <strong id="w-wind">8 km/h</strong>
                        </div>
                    </div>
                    <div class="w-metric-item">
                        <i class="fas fa-leaf"></i>
                        <div>
                            <span>AQI (शुद्ध हवा)</span>
                            <strong id="w-aqi" style="color: #10B981;">32 (उत्कृष्ट)</strong>
                        </div>
                    </div>
                </div>

                <div class="weather-travel-tip" id="w-tip">
                    <i class="fas fa-car"></i> <strong>यात्रा सलाह:</strong> धूप खिली होने से सभी प्रमुख मार्ग एवं कुफरी-नारकंडा खुला है।
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Popular News Widget -->
    <div class="widget">
        <div class="widget-header">
            <i class="fas fa-fire"></i> लोकप्रिय समाचार (Trending)
        </div>
        <div class="widget-body">
            <div class="popular-list">
                <?php $counter = 1; foreach ($popularArticles as $pop): ?>
                    <div class="popular-item">
                        <div class="popular-counter"><?= $counter++ ?></div>
                        <div class="popular-details">
                            <h4 class="popular-title">
                                <a href="article.php?slug=<?= urlencode($pop['slug']) ?>">
                                    <?= sanitize($pop['title']) ?>
                                </a>
                            </h4>
                            <div class="popular-meta">
                                <span><i class="far fa-clock"></i> <?= timeAgoHindi($pop['created_at']) ?></span>
                                <span><i class="far fa-eye"></i> <?= number_format($pop['views']) ?> व्यूज</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- 5. Newsletter Widget -->
    <div class="newsletter-widget">
        <div class="newsletter-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h3 class="newsletter-title">न्यूज़लेटर सब्सक्राइब करें</h3>
        <p class="newsletter-desc">हिमाचल प्रदेश की हर बड़ी खबर और देवभूमि दर्शन सीधे अपने इनबॉक्स में पाएं।</p>
        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('धन्यवाद! आपने सफलतापूर्वक हिमाचल न्यूज़ का न्यूज़लेटर सब्सक्राइब कर लिया है।'); this.reset();">
            <input type="email" placeholder="अपना ईमेल दर्ज करें..." required>
            <button type="submit"><i class="fas fa-paper-plane"></i> सब्सक्राइब करें</button>
        </form>
    </div>

</aside>
