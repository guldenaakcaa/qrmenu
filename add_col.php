<?php
$dsn = 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=mikaleyazilim_com_center';
$user = 'root';
$password = 'root';

try {
    $dbh = new PDO($dsn, $user, $password);
    $result = $dbh->query("SHOW COLUMNS FROM users LIKE 'api_token'")->fetchAll();
    if (count($result) == 0) {
        $dbh->exec("ALTER TABLE users ADD api_token VARCHAR(80) UNIQUE DEFAULT NULL AFTER password");
        echo "Column added.\n";
    } else {
        echo "Column already exists.\n";
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
?>
