<?php
$conn = ftp_connect('145.79.212.249', 21, 15);
ftp_login($conn, 'u238667987.news24hp.com', 'Rohit@301089');
ftp_pasv($conn, true);
ftp_put($conn, 'admin/create-admin.php', __DIR__ . '/../admin/create-admin.php', FTP_ASCII);
ftp_put($conn, 'admin/fix-admin-pass.php', __DIR__ . '/../admin/fix-admin-pass.php', FTP_ASCII);
ftp_put($conn, 'admin/login.php', __DIR__ . '/../admin/login.php', FTP_ASCII);
ftp_close($conn);
echo "Uploaded create-admin.php, fix-admin-pass.php, and login.php to FTP!\n";
