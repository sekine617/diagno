<?php
try {
    $db = new PDO('mysql:dbname=duo197_diagno;host=mysql1.php.xdomain.ne.jp;charset=utf8', 'duo197_user', 'Ehercy275dbha');
} catch(PDOException $e) {
    echo 'DB接続エラー: ' . $e->getMessage();
}
?>