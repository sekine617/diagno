<?php
session_start();
require_once('../dbconnect.php');


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

if (!empty($_POST['editCompleted'])) {
    if ($_POST['editName'] === '') {
        $error['editName'] = 'blank';
    }
    if ($_POST['editEmail'] === '') {
        $error['editEmail'] = 'blank';
    }
    $fileName = $_FILES['img']['name'];
    if(!empty($fileName)){
        $extention = substr($fileName, -3);
        if($extention != 'jpg' && $extention != 'gif' && $extention != 'png'){
            $error['img'] = 'type';
        }
    }
    if (empty($error)) {
        if (!empty($_FILES['img']['name'])) {
            $img = date('YmdHis') . $_FILES['img']['name'];
            move_uploaded_file($_FILES['img']['tmp_name'], '../images/members/' . $img);
        } else {
            $img = $_SESSION['profile']['img'];
        }
        echo $_POST['editName'];
        echo $_POST['editEmail'];
        echo $img;
        $sql = "UPDATE members SET name=:name, email=:email, picture=:picture WHERE id=" . $_SESSION['id'];
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $_POST['editName'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $_POST['editEmail'], PDO::PARAM_STR);
        $stmt->bindValue(':picture', $img, PDO::PARAM_STR);
        $stmt->execute();

        header('Location: manageAccount.php');
        exit();
    }
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
<?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>
<div class="profile_box edit_box">
        <div class="profile_title">
            <h1>プロフィール編集</h1>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="profile_contents edit_contents">
                <ul>
                    <li>
                        <div class="profile_content">
                            <div class="img_title">プロフィール画像：</div><img class="icon" src="../images/members/<?php echo $member['picture'] ?>">
                        </div>
                            <div>
                                追加したい画像を選択してください<br>
                                <input type="file" name='img'>
                            </div>
                            <?php if ($error['img'] === 'type') : ?>
                                <p>適切なファイルを選択してください</p>
                            <?php endif ?>
                        
                    </li>
                    <hr>
                    <li>
                    <div class="profile_content"><div class="content_title edit_title">ユーザー名：</div><input type="text" name="editName" value="<?php echo $member['name']; ?>"></div>
                        <?php if ($error['editName'] === 'blank') : ?>
                            <p>ユーザー名を入力してください</p>
                        <?php endif ?>
                    </li>
                    <hr>
                    <li>
                    <div class="profile_content"><div class="content_title edit_title">ユーザーID：</div>変更不可</div>
                    </li>
                    <hr>
                    <li>
                    <div class="profile_content"><div class="content_title edit_title">メールアドレス：</div><input type="text" name="editEmail" value="<?php echo $member['email']; ?>"></div>
                        <?php if ($error['editEmail'] === 'blank') : ?>
                            <p>メールアドレスを入力してください</p>
                        <?php endif ?>
                    </li>
                    <hr>
                </ul>
                <input type="submit" name="editCompleted" value="編集してマイページに戻る" class="edit_complete">
            </div>
        </form>
    </div>
</body>

</html>