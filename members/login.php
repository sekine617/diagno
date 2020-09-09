<?php
session_start();
require_once('../dbconnect.php');


// if($_COOKIE['email'] !==''){
//     var_dump($_COOKIE['email']);
// }

if (!empty($_POST)) {
    if (empty($_POST['email'])) {
        $error['email'] = 'blank';
    }
    if (empty($_POST['password'])) {
        $error['password'] = 'blank';
    }
    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        $sqlEmail = 'SELECT * FROM members WHERE email="' . $_POST['email'] . '"';
        $Email = $db->query($sqlEmail);
        $loginEmail = $Email->fetch();
        $sqlPass = 'SELECT * FROM members WHERE password="' . sha1($_POST['password']) . '"';
        $Pass = $db->query($sqlPass);
        $loginPass = $Pass->fetch();
        if ($loginEmail && $loginPass) {
            $sql = 'SELECT * FROM members WHERE email="' . $_POST['email'] . '" AND password="' . sha1($_POST['password']) . '"';
            $login = $db->query($sql);
            $member = $login->fetch();
            if ($member) {
                $_SESSION['id'] = $member['id'];
                $_SESSION['time'] = time();

                if ($_POST['save'] === 'on') {
                    setcookie('email', $_POST['email'], time() + 3600 * 24);
                }

                if ($_REQUEST['action'] == 'loginAgain') {
                    $backUrl = $_SESSION['backUrl'];
                    $location = 'Location: ' . $backUrl;
                    header($location);
                    exit();
                }

                header('Location: ../diagnosisPages/index.php');
                exit();
            } else {
                $error['login'] = 'failed';
            }
        } else
        if (!$loginEmail) {
            $error['email'] = 'notMatch';
        }
        if (!$loginPass) {
            $error['password'] = 'notMatch';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagno/menuHeader.php'); ?>
    <div id="wrap">

        <div class="login_content">
            <div class="login_title">
                <div id="head">
                    <p>メールアドレスでログイン</p>
                </div>
                <div id="lead">
                    <p>新規登録は<a href="../sign_up/index.php">こちら</a>から</p>
                </div>
            </div>
            <form action="" method="post">

                <div class="form_area">
                    <div class="form_group">
                        <input type="text" name="email" class="form" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" placeholder="メールアドレス">
                        <?php if ($error['email'] === 'blank') : ?>
                            <p class="error">メールアドレスを入力してください</p>
                        <?php endif ?>
                        <?php if ($error['email'] === 'notMatch') : ?>
                            <p class="error">メールアドレスが違います</p>
                        <?php endif ?>
                    </div>
                    <div class="form_group">
                        <input type="password" name="password" class="form" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" placeholder="パスワード">
                        <?php if ($error['password'] === 'blank') : ?>
                            <p class="error">パスワードを入力してください</p>
                        <?php endif ?>
                        <?php if ($error['password'] === 'notMatch') : ?>
                            <p class="error">パスワードが違います</p>
                        <?php endif ?>
                    </div>

                    <div class="auto_login">
                        <input id="save" type="checkbox" name="save" value="on">
                        <label for="save">次回からは自動的にログインする</label>
                    </div>

                    <div>
                        <input type="submit" value="ログイン" class="login_btn" />
                    </div>
                    <a href="../twitter/login.php">
                        <div class="submit_by_twitter"><span class="submit_tw_txt"><i class="fab fa-twitter"></i>
                                ツイッターでログイン</span></div>
                    </a>
                </div>

            </form>
        </div>
    </div>
</body>

</html>