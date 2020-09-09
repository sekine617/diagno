<?php
session_start();
ini_set('display_errors', 1);
require_once('../dbconnect.php');
require_once('ReviewForm.php');
require_once('reviewData.php');
require_once('Assay.php');
require_once('../clearSession.php');


// var_dump($_SESSION);
$assayNames = array('sweetness', 'acidity', 'mellow', 'richness', 'freshness', 'peculiar', 'preferSweet', 'preferAcid', 'preferMellow', 'preferRich', 'preferFresh', 'preferTexture', 'preferType');
if (!empty($_POST['start'])) {
    foreach ($assayNames as $assayName) {
        if (htmlspecialchars($_POST[$assayName], ENT_QUOTES) === '') {
            $error[$assayName] = 'blank';
        }
    }
    // var_dump($_POST['itemId']);
    if ($_POST['itemId'] === NULL) {
        $error['itemId'] = 'blank';
    }

    if (empty($error)) {
        $_SESSION['question'] = $_POST;

        header('Location: result.php');
        exit();
    }
    $_SESSION['question'] = $_POST;
}


if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['itemId'])) {
    $_POST = $_SESSION;
}


if (!empty($_POST['selectedInfo'])) {
    $postItemInfo = explode(",", $_POST['selectedInfo']);
    $_SESSION['question']['itemId'] = $postItemInfo[0];
    $_SESSION['question']['itemName'] = $postItemInfo[1];
    $_SESSION['question']['img'] = $postItemInfo[2];
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
                        <input type="submit" id="submitBtn" >
                    </form>
                </ul>
            </div>
        <?php endif ?>

        <form action="" method="post">
            <?php if (!empty($_POST['selectedInfo']) | !empty($_SESSION['itemId']) | !empty($_POST['itemId'])) : ?>
                <div class="selected_item">
                    <?php $postItemInfo = explode(",", $selectedInfo); ?>
                    <?php if (!empty($_POST['selectedInfo'])) {
                        $value = $_SESSION['question']['itemId'];
                    } elseif (!empty($_SESSION['question']['itemId'])) {
                        $value = $_SESSION['question']['itemId'];
                    } ?>
                    <input type="hidden" name="itemId" value="<?php print(htmlspecialchars($value, ENT_QUOTES)); ?>">
                    <input type="hidden" name="itemName" value="<?php print($_SESSION['question']['itemName']); ?>">
                    <input type="hidden" name="img" value="<?php print($_SESSION['question']['img']); ?>">
                    <img class="selected_img" src="../images/yogurt_img/<?php print($_SESSION['question']['img']); ?>">
                    <p class="selected_itemName"><?php echo $_SESSION['question']['itemName']; ?></p>
                </div>
            <?php endif ?>
            <?php if ($error['itemId'] === 'blank') : ?>
                <p class='error'>商品を選択してください</p>
            <?php endif ?>

            <hr>


            <?php for ($i = 0; $i < count($questionAssayContents); $i++) : ?>
                <div class="assays">
                    <?php $assay = $questionAssays[$i]; ?>
                    <?php if ($i <= 5) : ?>
                        <div class="title_box">
                            <p class="assay_title">質問<?php echo $i + 1; ?>: 選択された商品の<?php echo $assay->getName(); ?>についてお答えください</p>
                        </div>
                    <?php else : ?>
                        <div class="title_box">
                            <p class="assay_title">質問<?php echo $i + 1; ?>: 希望される<?php echo $assay->getName(); ?>についてお答えください</p>
                        </div>
                    <?php endif ?>

                    <?php $assayContent = $questionAssayContents[$i]; ?>
                    <?php foreach ($assayContent as $radios) : ?>
                        <?php $assayRadioId = $assay->getEvalName() . $radios->getValue(); ?>
                        <?php if ($_SESSION['question'][$assay->getEvalName()] === $radios->getValue()) : ?>
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
                </div>
                <?php if (htmlspecialchars($error[$assay->getEvalName()], ENT_QUOTES) === 'blank') : ?>
                    <p class="error">いずれかの選択肢を選択してください</p>
                <?php endif ?>
            <?php endfor ?>

            <input type="submit" name="start" value="診断開始！" id="submit_btn">
        </form>
    </div>

    <script src="main.js"></script>
</body>

</html>