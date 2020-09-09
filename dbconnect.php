<?php
try {
    $db = new PDO('mysql:dbname=*****;host=mysql1.php.xdomain.ne.jp;charset=utf8', ****', '******');
} catch(PDOException $e) {
    echo 'DB接続エラー: ' . $e->getMessage();
}
?>
