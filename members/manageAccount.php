<?php
session_start();
require_once('../dbconnect.php');
ini_set('display_errors', 1);

if (isset($_SESSION['id']) && $_SESSION['time'] + 36000 > time()) {
    $_SESSION['time'] = time();
    $sql = 'SELECT * FROM members WHERE id ="' . $_SESSION['id'] . '"';
    $stmt = $db->query($sql);
    $member = $stmt->fetch();
} else {
    $_SESSION['backUrl'] = '../members/manageAccount.php';
    header('Location: ../members/login.php?action=loginAgain');
    exit();
}

$sql = 'SELECT userID,name,email,picture FROM members WHERE id=' . $_SESSION['id'];
$stmt = $db->query($sql);
$member = $stmt->fetch();

$_SESSION['profile']['img'] = $member['picture'];
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
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>
    <div class="profile_box">
        <div class="profile_title">
            <h1>プロフィール</h1>
        </div>
        <div class="profile_contents">
            <ul>
                <li>
                    <div class="profile_content"><div class="img_title" >プロフィール画像：</div><img class="icon" src="../images/members/<?php echo $member['picture'] ?>"></div>
                </li>
                <hr>
                <li>
                <div class="profile_content"><div class="content_title">ユーザー名：</div><span class="profile_text"><?php echo $member['name']; ?></span></div>
                </li>
                <hr>
                <li>
                <div class="profile_content"><div class="content_title">ユーザーID :</div><span class="profile_text"><?php echo $member['userID']; ?></span></div>
                </li>
                <hr>
                <li>
                <div class="profile_content"><div class="content_title">メールアドレス :</div><span class="profile_text"><?php echo $member['email']; ?></span></div>
                </li>
                <hr>
            </ul>
            <a href="editProfile.php"><div class="edit_profile">プロフィールを編集する</div></a>
        </div>
    </div>
</body>

</html>