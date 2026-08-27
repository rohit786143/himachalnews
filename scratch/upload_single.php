<?php
$conn = ftp_connect('145.79.212.249', 21, 15);
ftp_login($conn, 'u238667987.news24hp.com', 'Rohit@301089');
ftp_pasv($conn, true);
ftp_put($conn, 'admin/check-users.php', __DIR__ . '/../admin/check-users.php', FTP_ASCII);
ftp_close($conn);
