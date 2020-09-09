<?php
require_once('../dbconnect.php');
session_start();
// ini_set('display_errors', 1);

$birthday = $_SESSION['sign_up']['year'].'-'.$_SESSION['sign_up']['month'].'-'.$_SESSION['sign_up']['day'];
$sex = htmlspecialchars($_SESSION['sign_up']['sex']);

if (!isset($_SESSION['sign_up'])) {
    header('Location: index.php');
    exit();
}
if(htmlspecialchars($_SESSION['sign_up']['sex'], ENT_QUOTES) == 1){
    $sessionSex = '男性';
}else{
    $sessionSex = '女性';
}
if(!empty($_POST)){
    $sql = 'INSERT INTO members (userID,name,sex,birthday,email,password)
VALUES (:userID,:name,:sex,:birthday,:email,:password)';
$stmt = $db->prepare($sql);
$stmt->bindValue(':userID', uniqid('', true), PDO::PARAM_STR);
$stmt->bindValue(':name', $_SESSION['sign_up']['name'], PDO::PARAM_STR);
$stmt->bindValue(':sex', $sex, PDO::PARAM_STR);
$stmt->bindValue(':birthday',$birthday, PDO::PARAM_STR);
$stmt->bindValue(':email', $_SESSION['sign_up']['email'], PDO::PARAM_STR);
$stmt->bindValue(':password', sha1($_SESSION['sign_up']['password']), PDO::PARAM_STR);
$stmt->execute();
unset($_SESSION['sign_up']);

header('Location: complete.php');
exit();
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Document</title>

</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/diagnosis_copy2/menuHeader.php'); ?>
    <div id="wrap">
    <div class="profile_box">
            <div id="head">
                <h1>登録内容確認</h1>
            </div>
            <div class="profile_contents">
                <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
                <form action="" method="post">
                    <input type="hidden" name="action" value="submit" />
                    <ul>
                        <li>
                            <div class="profile_content">
                                <div class="content_title">ユーザー名：</div>
                                <span class="profile_text"><?php print(htmlspecialchars($_SESSION['sign_up']['name'], ENT_QUOTES)) ?></span>
                            </div>
                        </li>
                        <hr>
                        <li>
                            <div class="profile_content">
                                <div class="content_title">性別：</div>
                                <span class="profile_text"><?php print(htmlspecialchars($sessionSex, ENT_QUOTES)) ?></span>
                            </div>
                        </li>
                        <hr>
                        <li>
                            <div class="profile_content">
                                <div class="content_title">生年月日：</div>
                                <span class="profile_text"><?php print(htmlspecialchars($_SESSION['sign_up']['year'] . '年 ' . $_SESSION['sign_up']['month'] . '月 ' . $_SESSION['sign_up']['day'] . '日 ', ENT_QUOTES)) ?></span>
                            </div>
                        </li>
                        <hr>
                        <li>
                            <div class="profile_content">
                                <div class="content_title">メールアドレス：</div>
                                <span class="profile_text"><?php print(htmlspecialchars($_SESSION['sign_up']['email'], ENT_QUOTES)) ?></span>
                            </div>
                        </li>
                        <hr>
                        <li>
                            <div class="profile_content">
                                <div class="content_title">パスワード：</div>
                                <span class="profile_text">表示されません</span>
                            </div>
                        </li>
                        <hr>
                    </ul>
                    <div class="confirm_btn">
                        <div class="rewrite"><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a></div>
                        <div class="complete_btn"><input type="submit" value="登録する" class="complete_btn"></div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>