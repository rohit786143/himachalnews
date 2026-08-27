<?php
/**
 * Real-Time Cricket Score API
 * News 24 Himachal
 * Returns real-time ongoing cricket matches and top tournaments
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

function getLiveCricketData() {
    $cacheFile = __DIR__ . '/../scratch/cricket_cache.json';
    $cacheTime = 25; // 25 seconds cache to stay fresh while avoiding spam

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        $cached = @file_get_contents($cacheFile);
        if ($cached) {
            $data = json_decode($cached, true);
            if (!empty($data)) {
                return $data;
            }
        }
    }

    $map = [
        'India' => ['flag' => '🇮🇳', 'hi' => 'भारत'],
        'Sri Lanka' => ['flag' => '🇱🇰', 'hi' => 'श्रीलंका'],
        'Australia' => ['flag' => '🇦🇺', 'hi' => 'ऑस्ट्रेलिया'],
        'England' => ['flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'hi' => 'इंग्लैंड'],
        'Pakistan' => ['flag' => '🇵🇰', 'hi' => 'पाकिस्तान'],
        'South Africa' => ['flag' => '🇿🇦', 'hi' => 'दक्षिण अफ्रीका'],
        'New Zealand' => ['flag' => '🇳🇿', 'hi' => 'न्यूजीलैंड'],
        'West Indies' => ['flag' => '🌴', 'hi' => 'वेस्टइंडीज'],
        'Bangladesh' => ['flag' => '🇧🇩', 'hi' => 'बांग्लादेश'],
        'Afghanistan' => ['flag' => '🇦🇫', 'hi' => 'अफगानिस्तान'],
        'Ireland' => ['flag' => '🇮🇪', 'hi' => 'आयरलैंड'],
        'Zimbabwe' => ['flag' => '🇿🇼', 'hi' => 'जिम्बाब्वे'],
        'North Zone' => ['flag' => '🏏', 'hi' => 'नॉर्थ ज़ोन'],
        'West Zone' => ['flag' => '🏏', 'hi' => 'वेस्ट ज़ोन'],
        'East Zone' => ['flag' => '🏏', 'hi' => 'ईस्ट ज़ोन'],
        'South Zone' => ['flag' => '🏏', 'hi' => 'साउथ ज़ोन'],
        'Central Zone' => ['flag' => '🏏', 'hi' => 'सेंट्रल ज़ोन'],
        'North East Zone' => ['flag' => '🏏', 'hi' => 'नॉर्थ ईस्ट ज़ोन'],
        'Antigua and Barbuda Falcons' => ['flag' => '🦅', 'hi' => 'एंटीगुआ फाल्कन्स'],
        'Barbados Tridents' => ['flag' => '🔱', 'hi' => 'बारबाडोस ट्राइडेंट्स'],
        'Adelaide Strikers Academy' => ['flag' => '⚡', 'hi' => 'एडिलेड स्ट्राइकर्स'],
        'Bangladesh High Performance XI' => ['flag' => '🇧🇩', 'hi' => 'बांग्लादेश HP']
    ];

    $resolveMeta = function($name) use ($map) {
        $name = trim($name);
        foreach ($map as $key => $val) {
            if (stripos($name, $key) !== false) {
                return $val;
            }
        }
        return ['flag' => '🏏', 'hi' => $name];
    };

    $xmlStr = @file_get_contents('https://www.espncricinfo.com/rss/livescores.xml', false, stream_context_create([
        'http' => ['timeout' => 4, 'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"]
    ]));

    $matches = [];
    if ($xmlStr) {
        $xml = @simplexml_load_string($xmlStr);
        if ($xml && isset($xml->channel->item)) {
            foreach ($xml->channel->item as $item) {
                $rawTitle = (string)$item->title;
                $link = (string)$item->link;

                $parts = explode(' v ', $rawTitle);
                if (count($parts) !== 2) {
                    $parts = explode(' vs ', $rawTitle);
                }
                if (count($parts) !== 2) continue;

                $t1Raw = trim($parts[0]);
                $t2Raw = trim($parts[1]);

                $t1Batting = (strpos($t1Raw, '*') !== false);
                $t2Batting = (strpos($t2Raw, '*') !== false);

                $t1Clean = trim(str_replace('*', '', $t1Raw));
                $t2Clean = trim(str_replace('*', '', $t2Raw));

                preg_match('/^(.*?)\s+((?:\d+\/\d+|\d+)(?:\s*&\s*\d+\/\d+)?)$/u', $t1Clean, $m1);
                if (!empty($m1[1])) {
                    $t1Name = trim($m1[1]);
                    $t1Score = trim($m1[2]);
                } else {
                    $t1Name = $t1Clean;
                    $t1Score = 'अभी बैटिंग नहीं';
                }

                preg_match('/^(.*?)\s+((?:\d+\/\d+|\d+)(?:\s*&\s*\d+\/\d+)?)$/u', $t2Clean, $m2);
                if (!empty($m2[1])) {
                    $t2Name = trim($m2[1]);
                    $t2Score = trim($m2[2]);
                } else {
                    $t2Name = $t2Clean;
                    $t2Score = 'अभी बैटिंग नहीं';
                }

                $meta1 = $resolveMeta($t1Name);
                $meta2 = $resolveMeta($t2Name);

                $isIndia = (stripos($t1Name, 'India') !== false || stripos($t2Name, 'India') !== false);
                
                // Determine series title
                if ($isIndia) {
                    $seriesTitle = "इंटरनेशनल सीरीज • लाइव मैच";
                } elseif (stripos($t1Name, 'Zone') !== false) {
                    $seriesTitle = "दलीप ट्रॉफी 2026 • लाइव मैच";
                } elseif (stripos($t1Name, 'Falcons') !== false || stripos($t2Name, 'Tridents') !== false) {
                    $seriesTitle = "CPL T20 लीग • लाइव मुकाबला";
                } else {
                    $seriesTitle = "टॉप टूर्नामेंट • लाइव क्रिकेट मैच";
                }

                if ($t1Batting) {
                    $status = "{$meta1['hi']} {$t1Score} पर लाइव खेल रही है";
                } elseif ($t2Batting) {
                    $status = "{$meta2['hi']} {$t2Score} पर लाइव खेल रही है";
                } else {
                    $status = "मैच लाइव अपडेट";
                }

                $matches[] = [
                    'id' => md5($rawTitle),
                    'series' => $seriesTitle,
                    'is_india' => $isIndia,
                    'status_hi' => $status,
                    'link' => $link,
                    'team1' => [
                        'name' => $meta1['hi'],
                        'name_en' => $t1Name,
                        'flag' => $meta1['flag'],
                        'score' => $t1Score,
                        'is_batting' => $t1Batting
                    ],
                    'team2' => [
                        'name' => $meta2['hi'],
                        'name_en' => $t2Name,
                        'flag' => $meta2['flag'],
                        'score' => $t2Score,
                        'is_batting' => $t2Batting
                    ]
                ];
            }
        }
    }

    // Sort India matches first, then active batting matches
    usort($matches, function($a, $b) {
        if ($a['is_india'] && !$b['is_india']) return -1;
        if (!$a['is_india'] && $b['is_india']) return 1;
        return 0;
    });

    // Fallback if network fails
    if (empty($matches)) {
        $matches[] = [
            'id' => 'default_live',
            'series' => 'ICC इंटरनेशनल क्रिकेट 2026',
            'is_india' => true,
            'status_hi' => 'मैच लाइव अपडेट',
            'link' => 'category.php?cat=sports',
            'team1' => [
                'name' => 'भारत',
                'name_en' => 'India',
                'flag' => '🇮🇳',
                'score' => 'लाइव अपडेट',
                'is_batting' => true
            ],
            'team2' => [
                'name' => 'श्रीलंका',
                'name_en' => 'Sri Lanka',
                'flag' => '🇱🇰',
                'score' => 'लाइव अपडेट',
                'is_batting' => false
            ]
        ];
    }

    $out = [
        'success' => true,
        'updated_at' => date('H:i:s'),
        'total' => count($matches),
        'matches' => array_slice($matches, 0, 5) // Top 5 live tournament matches
    ];

    @file_put_contents($cacheFile, json_encode($out, JSON_UNESCAPED_UNICODE));
    return $out;
}

$data = getLiveCricketData();
echo json_encode($data, JSON_UNESCAPED_UNICODE);
