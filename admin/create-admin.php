<?php
/**
 * Create / Reset Default Admin User
 * News 24 Himachal
 * 
 * Usage:
 * - Browser: Open http://localhost:8000/admin/create-admin.php or https://yourdomain.com/admin/create-admin.php
 * - Terminal: php admin/create-admin.php
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pdo = getDBConnection();
$message = '';
$isSuccess = false;

// 1. Ensure `users` table exists
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `username` VARCHAR(100) NOT NULL UNIQUE,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
            `designation` VARCHAR(150) DEFAULT 'संपादकीय प्रमुख',
            `location` VARCHAR(150) DEFAULT 'शिमला, हिमाचल प्रदेश',
            `avatar` VARCHAR(500) NULL,
            `bio` TEXT NULL,
            `social_twitter` VARCHAR(255) NULL,
            `social_facebook` VARCHAR(255) NULL,
            `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_users_username` (`username`),
            INDEX `idx_users_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 2. Admin Credentials
    $adminName = 'मुख्य संपादक (Chief Editor)';
    $adminUsername = 'admin';
    $adminEmail = 'admin@news24hp.com';
    $adminPasswordPlain = 'admin123';
    $adminHash = password_hash($adminPasswordPlain, PASSWORD_BCRYPT);

    // Check if table has `full_name` or `name`
    $cols = $pdo->query("SHOW COLUMNS FROM `users`")->fetchAll(PDO::FETCH_COLUMN);
    $nameCol = in_array('full_name', $cols) ? 'full_name' : 'name';

    // Update existing admin or insert new
    $checkStmt = $pdo->prepare("SELECT id FROM `users` WHERE `username` = ? OR `id` = 1 LIMIT 1");
    $checkStmt->execute([$adminUsername]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        $upd = $pdo->prepare("UPDATE `users` SET `password` = ?, `role` = 'admin', `status` = 'active', `username` = 'admin', `{$nameCol}` = ? WHERE `id` = ?");
        $upd->execute([$adminHash, $adminName, $existing['id']]);
    } else {
        $ins = $pdo->prepare("INSERT INTO `users` (`username`, `email`, `password`, `role`, `status`, `{$nameCol}`) VALUES (?, ?, ?, 'admin', 'active', ?)");
        $ins->execute([$adminUsername, $adminEmail, $adminHash, $adminName]);
    }

    // 4. Ensure Navigation Categories are Configured (is_nav column & 9 main items)
    ensureNavCategoriesConfigured($pdo);

    $isSuccess = true;
    $message = "एडमिन खाता और होम नेविगेशन बार श्रेणियां सफलतापूर्वक तैयार (Configured) कर दी गई हैं!";

} catch (PDOException $e) {
    $isSuccess = false;
    $message = "डेटाबेस त्रुटि: " . $e->getMessage();
}

// If executed via CLI / Terminal
if (php_sapi_name() === 'cli') {
    if ($isSuccess) {
        echo "\n=========================================\n";
        echo " [SUCCESS] Admin Account Created / Reset \n";
        echo "=========================================\n";
        echo " Username : admin\n";
        echo " Password : admin123\n";
        echo " Role     : admin (Super Administrator)\n";
        echo " Email    : admin@news24hp.com\n";
        echo "=========================================\n\n";
    } else {
        echo "\n[ERROR] " . $message . "\n\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>एडमिन क्रिएटर | News 24 Himachal</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Hind:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #F8FAFC;
            --primary: #E31B23;
            --primary-hover: #C41219;
            --primary-blue: #2F3E9E;
            --text-heading: #0F172A;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --border-color: #E2E8F0;
            --radius-md: 14px;
            --shadow-lg: 0 14px 34px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', 'Hind', -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .card {
            background: #FFFFFF;
            width: 100%;
            max-width: 480px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .card-header {
            background: #101935;
            padding: 28px 24px;
            text-align: center;
            border-bottom: 4px solid var(--primary);
        }

        .card-header i.brand-icon {
            width: 52px;
            height: 52px;
            background: var(--primary);
            color: #FFFFFF;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 12px;
            box-shadow: 0 4px 16px rgba(229, 9, 20, 0.5);
        }

        .card-header h1 {
            color: #FFFFFF;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .card-header p {
            color: #94A3B8;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .card-body {
            padding: 28px 26px;
        }

        .status-box {
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .status-success {
            background: #DCFCE7;
            border: 1px solid #BBF7D0;
            color: #166534;
        }

        .status-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
        }

        .cred-table {
            width: 100%;
            background: #F8FAFC;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .cred-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.92rem;
        }

        .cred-row:last-child {
            border-bottom: none;
        }

        .cred-label {
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cred-value {
            font-family: monospace;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-heading);
            background: #FFFFFF;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #CBD5E1;
        }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: var(--primary);
            color: #FFFFFF;
            text-decoration: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(229, 9, 20, 0.3);
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(229, 9, 20, 0.4);
        }

        .card-footer {
            padding: 14px 26px 18px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            background: #F8FAFC;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <i class="fas fa-shield-halved brand-icon"></i>
        <h1>News 24 Himachal</h1>
        <p>व्यवस्थापक खाता सेटअप (Admin Account Setup)</p>
    </div>

    <div class="card-body">
        <?php if ($isSuccess): ?>
            <div class="status-box status-success">
                <i class="fas fa-circle-check" style="font-size: 1.4rem;"></i>
                <div><?= sanitize($message) ?></div>
            </div>

            <div class="cred-table">
                <div class="cred-row">
                    <span class="cred-label"><i class="fas fa-user"></i> यूज़रनेम (Username)</span>
                    <span class="cred-value">admin</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label"><i class="fas fa-key"></i> पासवर्ड (Password)</span>
                    <span class="cred-value">admin123</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label"><i class="fas fa-user-shield"></i> पद (Role)</span>
                    <span class="cred-value" style="color: #16A34A;">Super Admin</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label"><i class="fas fa-envelope"></i> ईमेल (Email)</span>
                    <span class="cred-value" style="font-size: 0.85rem;">admin@news24hp.com</span>
                </div>
            </div>

            <a href="/admin/login.php" class="btn-login">
                <i class="fas fa-right-to-bracket"></i> एडमिन लॉगिन पेज पर जाएं
            </a>
        <?php else: ?>
            <div class="status-box status-error">
                <i class="fas fa-triangle-exclamation" style="font-size: 1.4rem;"></i>
                <div><?= sanitize($message) ?></div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-footer">
        सुरक्षा सलाह: लाइव सर्वर पर सेटअप पूर्ण होने के बाद इस स्क्रिप्ट को सुरक्षित रखें।
    </div>
</div>

</body>
</html>
