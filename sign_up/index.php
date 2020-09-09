<?php
session_start();
require_once('../dbconnect.php');
// ini_set('display_errors', 1);


if (!empty($_POST)) {

    if ($_POST['name'] === '') {
        $error['name'] = 'blank';
    } else {
        $sql = 'SELECT COUNT(*) AS count FROM members WHERE name= ' . '"' . $_POST['name'] . '"';
        $member = $db->query($sql);
        $name = $member->fetchColumn();
        if ($name['count'] > 0) {
            $error['name'] = 'duplicate';
        }
    }
    if ($_POST['sex'] === '') {
        $error['sex'] = 'blank';
    }
    if ($_POST['year'] === '' | $_POST['month'] === '' | $_POST['day'] === '') {
        $error['BOD'] = 'blank';
    }
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'inappropriate';
    } else {
        $sql = 'SELECT COUNT(*) AS count FROM members WHERE email= ' . '"' . $_POST['email'] . '"';
        $member = $db->query($sql);
        $email = $member->fetchColumn();
        if ($email['count'] > 0) {
            $error['email'] = 'duplicate';
        }
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    } elseif (strlen($_POST['password']) < 4) {
        $error['password'] = 'short';
    }

    if ($_POST['sex'] == '') {
        $error['sex'] = 'blank';
    }

    if ($_POST['year'] == 'null' | $_POST['year'] == 'null' | $_POST['year'] == 'null') {
        $error['DOB'] = 'blank';
    }

    if (empty($error)) {
        $_SESSION['sign_up'] = $_POST;
        header('Location: confirm.php');
        exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['sign_up'])) {
    $_POST = $_SESSION['sign_up'];
}


for ($i = 1920; $i <= 2020; $i++) {
    if ($i == $_POST['year']) {
        $year .= '<option selected value="' . $i . '">' . $i . '</option>';
    } else {
        $year .= '<option value="' . $i . '">' . $i . '</option>';
    }
}
for ($i = 1; $i <= 12; $i++) {
    if ($i == $_POST['month']) {
        $month .= '<option selected value="' . $i . '">' . $i . '</option>';
    } else {
        $month .= '<option value="' . $i . '">' . $i . '</option>';
    }
}
for ($i = 1; $i <= 31; $i++) {
    if ($i == $_POST['day']) {
        $day .= '<option selected value="' . $i . '">' . $i . '</option>';
    } else {
        $day .= '<option value="' . $i . '">' . $i . '</option>';
    }
}



if ($_POST['sex'] == 1) {
    $male = '<input type="radio" name="sex" value="1" id="male" checked="checked" class="radio-inline__input" style="display:none"><label for="male" class="radio-inline__label">男性</label>';
    $female = '<input type="radio" name="sex" value="2" id="female" class="radio-inline__input" style="display:none"><label for="female" class="radio-inline__label">女性</label>';
} elseif ($_POST['sex'] == 2) {
    $female = '<input type="radio" name="sex" value="2" id="female" checked="checked" class="radio-inline__input" style="display:none"><label for="female" class="radio-inline__label">女性</label>';
    $male = '<input type="radio" name="sex" value="1" id="male" class="radio-inline__input" style="display:none"><label for="male" class="radio-inline__label">男性</label>';
} else {
    $male = '<input type="radio" name="sex" value="1" id="male" class="radio-inline__input" style="display:none"><label for="male" class="radio-inline__label">男性</label>';
    $female = '<input type="radio" name="sex" value="2" id="female" class="radio-inline__input" style="display:none"><label for="female" class="radio-inline__label">女性</label>';
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
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/diagnosis_copy2/menuHeader.php'); ?>
    <div id="sign_up_content">
        <div id="head">
            <p>新規登録</p>
        </div>


        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
            <ul>
            <div class="sign_up_form">
                    <li>ユーザーネーム<span class="required">*必須</span></li>
                    <li>
                        <input type="text" name="name" size="35" maxlength="255" placeholder="ユーザーネーム" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)) ?>">
                        <?php if ($error['name'] === 'blank') : ?>
                            <p class="error">ユーザーネームを入力してください</p>
                        <?php elseif ($error['name'] === 'duplicate') : ?>
                            <p class="error">既に使用されています</p>
                        <?php endif ?>
                    </li>
                </div>

                <div class="sign_up_form">
                    <li>性別<span class="required">*必須</span></li>
                    <li class="sexForm">
                        <?php echo $male;
                        echo $female; ?>

                        <?php if ($error['sex'] === 'blank') : ?>
                            <p class="error">性別を入力してください</p>
                        <?php endif ?>
                    </li>
                </div>

                <div class="sign_up_form">
                    <li>生年月日<span class="required">*必須</span></li>
                    <li class="birthday">
                        <div class="birthady">
                            <select name="year">
                                <option value="null">選択</option><?php echo $year ?>
                            </select>年


                            <select name="month">
                                <option value="null">選択</option><?php echo $month ?>
                            </select>月


                            <select name="day">
                                <option value="null">選択</option><?php echo $day ?>
                            </select>日
                        </div>
                        <?php if ($error['DOB'] === 'blank') : ?>
                            <p class="error">生年月日を入力してください</p>
                        <?php endif ?>
                    </li>
                </div>

                <div class="sign_up_form">
                    <li>メールアドレス<span class="required">*必須</span></li>
                    <li>
                        <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)) ?>" placeholder="メールアドレス">
                        <?php if ($error['email'] === 'blank') : ?>
                            <p class="error">メールアドレスを入力してください</p>

                        <?php elseif ($error['email'] === 'duplicate') : ?>
                            <p class="error">既に登録済みのメールアドレスです</p>

                        <?php elseif ($error['email'] === 'inappropriate') : ?>
                            <p class="error">適切なメールアドレスを入力してください</p>
                        <?php endif ?>
                    </li>
                </div>
                <div class="sign_up_form">
                    <li>パスワード<span class="required">*必須</span></li>
                    <li>
                        <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)) ?>" placeholder="パスワード（4文字以上）" />
                        <?php if ($error['password'] === 'blank') : ?>
                            <p class="error">パスワードを入力してください</p>
                        <?php endif ?>
                        <?php if ($error['password'] === 'short') : ?>
                            <p class="error">4文字以上で入力してください</p>
                        <?php endif ?>
                    </li>
                </div>

            </ul>
            <div><input type="submit" value="入力内容を確認する" class="check_btn"></div>
            <a href="../twitter/login.php">
                <div class="submit_by_twitter">
                    <span class="submit_tw_txt"><i class="fab fa-twitter"></i>
                        ツイッターで登録</span></div>
            </a>
        </form>
    </div>
</body>

</html>