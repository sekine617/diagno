<?php
session_start();
// ini_set('display_errors', 1);
require_once('../dbconnect.php');
require_once('ReviewForm.php');
require_once('reviewData.php');
require_once('Assay.php');
require_once('../clearSession.php');

$assayNames = array('sweetness', 'acidity', 'mellow', 'richness', 'freshness', 'peculiar', 'texture', 'type', 'category');
if (!empty($_POST['start'])) {
    foreach ($assayNames as $assayName) {
        if (htmlspecialchars($_POST[$assayName], ENT_QUOTES) === '') {
            $error[$assayName] = 'blank';
        }
    }
    // var_dump($_POST['idBox']);
    if ($_POST['itemId'] === NULL) {
        $error['itemId'] = 'blank';
    }
    if ($_POST['reviewText'] === '') {
        $error['reviewText'] = 'blank';
    }
    if (strlen($_POST['reviewText']) < 50) {
        $error['reviewText'] = 'short';
        $shortage = 50 - strlen($_POST['reviewText']);
    }
    $_SESSION['review'] = $_POST;
    if (empty($error)) {
        $_SESSION['review'] = $_POST;
        // var_dump($_SESSION);
        header('Location: insertReview.php');
        exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['itemId'])) {
    $_POST = $_SESSION;
}

if (isset($_SESSION['id']) && $_SESSION['time'] + 36000 > time()) {
    $_SESSION['time'] = time();
    $sql = 'SELECT * FROM members WHERE id ="' . $_SESSION['id'] . '"';
    $stmt = $db->query($sql);
    $member = $stmt->fetch();
} else {
    $_SESSION['backUrl'] = '../diagnosisPages/writeReview.php';
    header('Location: ../members/login.php?action=loginAgain');
    exit();
}

if (!empty($_POST['selectedInfo'])) {
    $postItemInfo = explode(",", $_POST['selectedInfo']);
    $_SESSION['review']['itemId'] = $postItemInfo[0];
    $_SESSION['review']['itemName'] = $postItemInfo[1];
    $_SESSION['review']['img'] = $postItemInfo[2];
}

if (isset($_POST['search'])) {
    $search = htmlspecialchars($_POST['search']);
    $search_value = $search;
} else {
    $search = '';
    $search_value = '';
}
$like = "'" . '%' . $search_value . '%' . "'";
$sql = 'SELECT * FROM yogurtData WHERE itemName LIKE ' . $like;
$sqlCount = 'SELECT COUNT(*) FROM yogurtData WHERE itemName LIKE ' . $like;
$results = $db->query($sql);
$resultNum = $db->query($sqlCount);
$resultNum = $resultNum->fetchColumn();
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
    <div class="question_forms">
        <form action="" method="post">
            <input type="text" name="search" value="" placeholder="">
            <input type="submit" name="" value="検索" id="search_btn">
        </form>
        <?php if ($search_value == '' && empty($_POST['selectedInfo']) && empty($_POST['itemId'])) : ?>
            <p>キーワードを入力してください</p>
        <?php elseif ($resultNum == 0) : ?>
            <p>別のキーワードでお探しください</p>
        <?php elseif (!empty($_POST['selectedInfo']) | !empty($_POST['itemId'])) : ?>
            <p></p>
        <?php elseif ($resultNum !== 0) : ?>
            <p>以下からお選びください</p>
            <div class="results">
                <ul>
                    <form action="" method="post" name="resultForm">
                        <?php while ($result = $results->fetch()) : ?>
                            <?php $idName = "result" . $result['id']; ?>
                            <?php $itemInfo = $result['id'] . "," . $result['itemName'] . "," . $result['img']; ?>
                            <a href="javascript:void(0);" onclick="OnClick();">
                                <label class="" for="<?php echo $idName ?>">
                                    <li class="result">
                                        <img class="result_img" src="../images/yogurt_img/<?php echo $result['img']; ?>">
                                        <p><?php echo $result['itemName']; ?></p>
                                        <input id="<?php echo $idName ?>" type="radio" name="selectedInfo" class="result_radio" value="<?php echo $itemInfo; ?>">
                                    </li>
                                </label>
                            </a>
                            <hr>
                        <?php endwhile ?>
                        <input type="submit" value="" id="submitBtn">
                    </form>
                </ul>
            </div>
        <?php endif ?>

        <form action="" method="post">
            <?php if (!empty($_POST['selectedInfo']) | !empty($_SESSION['itemId']) | !empty($_POST['itemId'])) : ?>
                <div class="selected_item">
                    <?php $postItemInfo = explode(",", $selectedInfo); ?>
                    <?php if (!empty($_POST['selectedInfo'])) {
                        $value = $_SESSION['review']['itemId'];
                    } elseif (!empty($_SESSION['review']['itemId'])) {
                        $value = $_SESSION['review']['itemId'];
                    } ?>
                    <input type="hidden" name="itemId" value="<?php print(htmlspecialchars($value, ENT_QUOTES)); ?>">
                    <input type="hidden" name="itemName" value="<?php print($_SESSION['review']['itemName']); ?>">
                    <input type="hidden" name="img" value="<?php print($_SESSION['review']['img']); ?>">
                    <img class="selected_img" src="../images/yogurt_img/<?php print($_SESSION['review']['img']); ?>">
                    <p class="selected_itemName"><?php echo $_SESSION['review']['itemName']; ?></p>
                </div>
            <?php endif ?>
            <?php if ($error['itemId'] === 'blank') : ?>
                <p class="error">商品を選択してください</p>
            <?php endif ?>
            <hr>
            <div class="assays">

                <?php for ($i = 0; $i < 9; $i++) : ?>
                    <div class="assays">
                        <?php $assay = $reviewAssays[$i]; ?>
                        <?php if ($i <= 5) : ?>
                            <div class="title_box">
                                <p class="assay_title">質問<?php echo $i + 1; ?>: 選択された商品の<?php echo $assay->getName(); ?>についてお答えください</p>
                            </div>
                        <?php elseif ($i > 5) : ?>
                            <div class="title_box">
                                <p class="assay_title">質問<?php echo $i + 1; ?>: <?php echo $assay->getName(); ?>を選択してください</p>
                            </div>
                        <?php endif ?>
                        <?php $assayContent = $reviewAssayContents[$i]; ?>
                        <?php if ($i === 8) : ?>
                            <?php foreach ($assayContent as $radios) : ?>
                                <?php $assayRadioId = $assay->getEvalName() . $radios->getValue(); ?>
                                <?php $checked = 'off'; ?>
                                <?php if (!empty($_SESSION['review']['category'])) : ?>
                                <?php $postValues = $_SESSION['review'][$assay->getEvalName()];?>
                                <?php foreach ( $postValues as $postValue) : ?>
                                    <?php if ($postValue == $radios->getValue()) : ?>
                                        <?php $checked = 'on'; ?>
                                        <div class="assay">
                                            <input class="radio-inline__input" type="checkbox" name="category[]" value="<?php echo $radios->getValue(); ?>" id="<?php echo $assayRadioId; ?>" checked="checked">
                                            <label class="radio-inline__label" for="<?php echo $assayRadioId; ?>"><?php echo $radios->getLavel(); ?></label>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                                <?php endif ?>
                                <?php if ($checked !== 'on') : ?>
                                    <div class="assay">
                                        <input class="radio-inline__input" type="checkbox" name="category[]" value="<?php echo $radios->getValue(); ?>" id="<?php echo $assayRadioId; ?>">
                                        <label class="radio-inline__label" for="<?php echo $assayRadioId; ?>"><?php echo $radios->getLavel(); ?></label>
                                    </div>
                                <?php endif ?>

                            <?php endforeach ?>
                        <?php else : ?>
                            <?php foreach ($assayContent as $radios) : ?>
                                <?php $assayRadioId = $assay->getEvalName() . $radios->getValue(); ?>
                                <?php if ($_SESSION['review'][$assay->getEvalName()] == $radios->getValue()) : ?>
                                    <div class="assay">
                                        <input type="radio" class="radio-inline__input" name="<?php echo $assay->getEvalName(); ?>" value="<?php echo $radios->getValue(); ?>" id="<?php echo $assayRadioId; ?>" checked="checked">
                                        <label class="radio-inline__label" for="<?php echo $assayRadioId; ?>"><?php echo $radios->getLavel(); ?></label>
                                    </div>
                                <?php else : ?>
                                    <div class="assay">
                                        <input type="radio" class="radio-inline__input" name="<?php echo $assay->getEvalName(); ?>" value="<?php echo $radios->getValue(); ?>" id="<?php echo $assayRadioId; ?>">
                                        <label class="radio-inline__label" for="<?php echo $assayRadioId; ?>"><?php echo $radios->getLavel(); ?></label>
                                    </div>
                                <?php endif ?>

                            <?php endforeach ?>
                        <?php endif ?>
                        <?php if (htmlspecialchars($error[$assay->getEvalName()], ENT_QUOTES) === 'blank') : ?>
                            <p class="error">いずれかの選択肢を選択してください</p>
                        <?php endif ?>

                    <?php endfor ?>

                    <div class="title_box">
                        <p class="assay_title">この商品のレビューをご記入ください</p>
                    </div>
                    <textarea name="reviewText" cols="80" rows="10" placeholder="商品を試してみて感想・意見を50字以上でお書きください"><?php echo $_SESSION['review']['reviewText']; ?></textarea>
                    <?php if ($error['reviewText'] === 'short') : ?>
                        <p class=error>あと<?php echo $shortage; ?>文字</p>
                    <?php endif ?>
                    </div>
            </div>
            <input type="submit" name="start" value="レビューを投稿する" id="submit_btn">
        </form>

        <script src="main.js"></script>
</body>

</html>