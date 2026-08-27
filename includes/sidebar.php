<?php
/**
 * Sidebar Component
 * Live Cricket Score, Interactive Horoscope (Rashifal), Multi-City Himachal Weather, Popular News, Newsletter
 */
$popularArticles = getPopularNews($pdo, 5);
$recentArticles = getRecentNews($pdo, 4);
?>
<aside class="sidebar">

    <!-- 1. Live Real-Time Cricket Score Widget -->
    <?php
    // Fetch initial live match data
    $liveCricketData = null;
    $cacheFile = __DIR__ . '/../scratch/cricket_cache.json';
    if (file_exists($cacheFile)) {
        $liveCricketData = json_decode(@file_get_contents($cacheFile), true);
    }
    $primaryMatch = $liveCricketData['matches'][0] ?? null;
    ?>
    <div class="widget cricket-score-widget" id="cricketScoreWidget">
        <div class="widget-header" style="background: linear-gradient(135deg, #1E3A8A, #172554); border-bottom-color: #3B82F6; padding: 12px 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                <span style="font-weight: 800; font-size: 0.92rem; color: #FFFFFF; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-baseball-bat-ball" style="color: #60A5FA;"></i> लाइव क्रिकेट स्कोर
                </span>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span id="cricketLiveBadge" class="live-pulse-badge"><span class="pulse-dot"></span> LIVE</span>
                </div>
            </div>
        </div>

        <!-- Ongoing Match Selector Tabs (if multiple matches active) -->
        <?php if (!empty($liveCricketData['matches']) && count($liveCricketData['matches']) > 1): ?>
            <div id="cricketMatchTabs" style="display: flex; gap: 4px; overflow-x: auto; background: #0F172A; padding: 6px 10px; border-bottom: 1px solid #1E293B; scrollbar-width: none;">
                <?php foreach (array_slice($liveCricketData['matches'], 0, 4) as $idx => $m): ?>
                    <button type="button" class="cricket-tab-btn <?= $idx === 0 ? 'active' : '' ?>" 
                            data-index="<?= $idx ?>" 
                            style="padding: 4px 10px; border-radius: 4px; border: none; font-size: 0.72rem; font-weight: 700; white-space: nowrap; cursor: pointer; transition: all 0.2s ease; <?= $idx === 0 ? 'background: #3B82F6; color: #FFFFFF;' : 'background: rgba(255,255,255,0.08); color: #94A3B8;' ?>"
                            onclick="switchCricketMatch(<?= $idx ?>)">
                        <?= $m['team1']['flag'] ?> <?= sanitize($m['team1']['name']) ?> v <?= $m['team2']['flag'] ?> <?= sanitize($m['team2']['name']) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="widget-body cricket-card-body" id="cricketCardContainer" style="padding: 16px;">
            <div class="cricket-match-meta" id="cricketSeriesTitle" style="font-size: 0.8rem; font-weight: 700; color: #0284C7; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span><?= sanitize($primaryMatch['series'] ?? 'टॉप टूर्नामेंट • लाइव क्रिकेट मैच') ?></span>
                <span class="badge" style="background: #E0F2FE; color: #0284C7; font-size: 0.68rem; padding: 2px 6px;">आज का मैच</span>
            </div>
            
            <div class="cricket-teams-row" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px 14px;">
                <!-- Team 1 -->
                <div class="cricket-team <?= (!empty($primaryMatch['team1']['is_batting'])) ? 'active-team' : '' ?>" id="team1Box" style="text-align: center; flex: 1;">
                    <div class="team-flag-box" id="team1Flag" style="font-size: 1.8rem; line-height: 1; margin-bottom: 4px;">
                        <?= $primaryMatch['team1']['flag'] ?? '🏏' ?>
                    </div>
                    <div class="team-name" id="team1Name" style="font-weight: 800; font-size: 0.88rem; color: var(--text-heading); margin-bottom: 2px;">
                        <?= sanitize($primaryMatch['team1']['name'] ?? 'टीम 1') ?>
                    </div>
                    <div class="team-score <?= (!empty($primaryMatch['team1']['is_batting'])) ? 'highlight' : '' ?>" id="team1Score" style="font-size: 1.1rem; font-weight: 800; color: <?= (!empty($primaryMatch['team1']['is_batting'])) ? 'var(--primary-red)' : 'var(--text-main)' ?>;">
                        <?= sanitize($primaryMatch['team1']['score'] ?? 'लाइव') ?>
                    </div>
                    <?php if (!empty($primaryMatch['team1']['is_batting'])): ?>
                        <span id="team1BatTag" class="badge badge-red" style="font-size: 0.62rem; margin-top: 4px;">🏏 बैटिंग</span>
                    <?php else: ?>
                        <span id="team1BatTag" style="display:none;"></span>
                    <?php endif; ?>
                </div>

                <div class="cricket-vs" style="font-weight: 800; color: #94A3B8; font-size: 0.82rem; padding: 0 8px;">VS</div>

                <!-- Team 2 -->
                <div class="cricket-team <?= (!empty($primaryMatch['team2']['is_batting'])) ? 'active-team' : '' ?>" id="team2Box" style="text-align: center; flex: 1;">
                    <div class="team-flag-box" id="team2Flag" style="font-size: 1.8rem; line-height: 1; margin-bottom: 4px;">
                        <?= $primaryMatch['team2']['flag'] ?? '🏏' ?>
                    </div>
                    <div class="team-name" id="team2Name" style="font-weight: 800; font-size: 0.88rem; color: var(--text-heading); margin-bottom: 2px;">
                        <?= sanitize($primaryMatch['team2']['name'] ?? 'टीम 2') ?>
                    </div>
                    <div class="team-score <?= (!empty($primaryMatch['team2']['is_batting'])) ? 'highlight' : '' ?>" id="team2Score" style="font-size: 1.1rem; font-weight: 800; color: <?= (!empty($primaryMatch['team2']['is_batting'])) ? 'var(--primary-red)' : 'var(--text-main)' ?>;">
                        <?= sanitize($primaryMatch['team2']['score'] ?? 'लाइव') ?>
                    </div>
                    <?php if (!empty($primaryMatch['team2']['is_batting'])): ?>
                        <span id="team2BatTag" class="badge badge-red" style="font-size: 0.62rem; margin-top: 4px;">🏏 बैटिंग</span>
                    <?php else: ?>
                        <span id="team2BatTag" style="display:none;"></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Live Status / Commentary Tagline -->
            <div class="cricket-target-status" id="cricketStatusText" style="background: #FEF2F2; border: 1px solid #FECACA; border-radius: 6px; padding: 8px 12px; font-size: 0.82rem; color: #991B1B; margin-bottom: 14px; font-weight: 600; text-align: center;">
                <i class="fas fa-tower-broadcast" style="color: var(--primary-red); margin-right: 4px;"></i> 
                <span><?= sanitize($primaryMatch['status_hi'] ?? 'मैच लाइव प्रगति पर है') ?></span>
            </div>

            <div class="cricket-footer-action">
                <a id="cricketLiveLink" href="<?= sanitize($primaryMatch['link'] ?? 'category.php?cat=sports') ?>" target="_blank" rel="noopener" class="cricket-full-card-btn" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; border-radius: 6px; background: linear-gradient(135deg, #1E3A8A, #1D4ED8); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: transform 0.2s ease;">
                    <i class="fas fa-chart-line"></i> फुल लाइव स्कोरकार्ड एवं मैच सेंटर &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Live Cricket Auto-Updater Script -->
    <script>
    let currentLiveMatches = <?= json_encode($liveCricketData['matches'] ?? []) ?>;
    let selectedMatchIndex = 0;

    function renderMatchDetails(idx) {
        if (!currentLiveMatches || !currentLiveMatches[idx]) return;
        selectedMatchIndex = idx;
        const m = currentLiveMatches[idx];

        document.getElementById('cricketSeriesTitle').firstElementChild.textContent = m.series || 'लाइव क्रिकेट मैच';
        
        document.getElementById('team1Flag').textContent = m.team1.flag || '🏏';
        document.getElementById('team1Name').textContent = m.team1.name;
        document.getElementById('team1Score').textContent = m.team1.score;
        document.getElementById('team1Score').style.color = m.team1.is_batting ? 'var(--primary-red)' : 'var(--text-main)';
        
        const t1Tag = document.getElementById('team1BatTag');
        if (m.team1.is_batting) {
            t1Tag.className = 'badge badge-red';
            t1Tag.style.fontSize = '0.62rem';
            t1Tag.style.marginTop = '4px';
            t1Tag.style.display = 'inline-block';
            t1Tag.textContent = '🏏 बैटिंग';
        } else {
            t1Tag.style.display = 'none';
        }

        document.getElementById('team2Flag').textContent = m.team2.flag || '🏏';
        document.getElementById('team2Name').textContent = m.team2.name;
        document.getElementById('team2Score').textContent = m.team2.score;
        document.getElementById('team2Score').style.color = m.team2.is_batting ? 'var(--primary-red)' : 'var(--text-main)';

        const t2Tag = document.getElementById('team2BatTag');
        if (m.team2.is_batting) {
            t2Tag.className = 'badge badge-red';
            t2Tag.style.fontSize = '0.62rem';
            t2Tag.style.marginTop = '4px';
            t2Tag.style.display = 'inline-block';
            t2Tag.textContent = '🏏 बैटिंग';
        } else {
            t2Tag.style.display = 'none';
        }

        document.getElementById('cricketStatusText').lastElementChild.textContent = m.status_hi;
        if (m.link) {
            document.getElementById('cricketLiveLink').href = m.link;
        }

        // Update tab highlight
        const tabs = document.querySelectorAll('.cricket-tab-btn');
        tabs.forEach((tab, i) => {
            if (i === idx) {
                tab.style.background = '#3B82F6';
                tab.style.color = '#FFFFFF';
            } else {
                tab.style.background = 'rgba(255,255,255,0.08)';
                tab.style.color = '#94A3B8';
            }
        });
    }

    function switchCricketMatch(idx) {
        renderMatchDetails(idx);
    }

    // Auto-refresh live scores every 30 seconds
    function fetchLiveCricketUpdates() {
        fetch('/api/cricket-score.php')
            .then(res => res.json())
            .then(data => {
                if (data && data.success && data.matches && data.matches.length > 0) {
                    currentLiveMatches = data.matches;
                    renderMatchDetails(selectedMatchIndex < currentLiveMatches.length ? selectedMatchIndex : 0);
                }
            })
            .catch(err => console.log('Cricket update check:', err));
    }

    setInterval(fetchLiveCricketUpdates, 30000);
    </script>

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

    <!-- 4.5. Sidebar Advertisement Banner Widget (Placed right above Newsletter) -->
    <?php
    $adStatus = getSetting($pdo, 'ad_banner_status', 'active');
    $adImage = getSetting($pdo, 'ad_banner_image', '/assets/images/ad_banner.jpg');
    $adLink = getSetting($pdo, 'ad_banner_link', 'contact.php');
    $adTitle = getSetting($pdo, 'ad_banner_title', 'विज्ञापन स्थान (This Space is Available for Advertisement)');
    ?>
    <?php if ($adStatus === 'active' && !empty($adImage)): ?>
        <div class="widget advertisement-widget" style="background: #FFFFFF; border: 1.5px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 24px; box-shadow: var(--shadow-sm); transition: transform 0.2s ease;">
            <div style="padding: 7px 12px; background: #F8FAFC; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--text-dim); letter-spacing: 0.5px; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-rectangle-ad" style="color: var(--primary-red);"></i> प्रायोजित विज्ञापन (Sponsored Ad)
                </span>
                <a href="contact.php" style="font-size: 0.7rem; font-weight: 700; color: #0284C7; text-decoration: none;">
                    विज्ञापन दें &rarr;
                </a>
            </div>
            <div style="padding: 8px; text-align: center; background: #0F172A;">
                <a href="<?= sanitize($adLink) ?>" target="_blank" rel="noopener" style="display: block; overflow: hidden; border-radius: 8px;" title="<?= sanitize($adTitle) ?>">
                    <img src="<?= sanitize($adImage) ?>" alt="<?= sanitize($adTitle) ?>" 
                         style="width: 100%; height: auto; aspect-ratio: 1/1; object-fit: cover; display: block; transition: transform 0.3s ease;"
                         onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                </a>
            </div>
            <div style="padding: 8px 12px; background: #F8FAFC; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 0.74rem; color: var(--text-muted); font-weight: 600;">
                    <i class="fas fa-bullhorn" style="color: var(--primary-red); font-size: 0.7rem;"></i> यहाँ विज्ञापन लगाने हेतु
                </span>
                <a href="contact.php" class="btn-primary" style="padding: 4px 10px; font-size: 0.72rem; border-radius: 4px; text-decoration: none; font-weight: 700;">
                    संपर्क करें &rarr;
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- 5. Newsletter Widget -->
    <div class="newsletter-widget">
        <div class="newsletter-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h3 class="newsletter-title">न्यूज़लेटर सब्सक्राइब करें</h3>
        <p class="newsletter-desc">हिमाचल प्रदेश की हर बड़ी खबर और देवभूमि दर्शन सीधे अपने इनबॉक्स में पाएं।</p>
        <form class="newsletter-form" onsubmit="event.preventDefault(); alert('धन्यवाद! आपने सफलतापूर्वक News 24 Himachal का न्यूज़लेटर सब्सक्राइब कर लिया है।'); this.reset();">
            <input type="email" placeholder="अपना ईमेल दर्ज करें..." required>
            <button type="submit"><i class="fas fa-paper-plane"></i> सब्सक्राइब करें</button>
        </form>
    </div>

</aside>
